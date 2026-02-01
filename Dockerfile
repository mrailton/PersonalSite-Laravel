# Stage 1: Build assets
FROM node:20-alpine AS assets-builder
WORKDIR /app

# Install PHP and Composer to get vendor assets needed for the build
RUN apk add --no-cache php84 php84-common php84-iconv php84-gd php84-curl php84-xml php84-mysqli php84-imap php84-cgi php84-pdo php84-pdo_mysql php84-soap php84-posix php84-gettext php84-ldap php84-ctype php84-dom php84-simplexml php84-tokenizer php84-xmlwriter php84-zip php84-mbstring php84-bcmath php84-phar php84-openssl php84-session php84-fileinfo php84-intl php84-pdo_sqlite php84-pecl-redis curl composer

COPY composer.* ./
RUN composer install --no-interaction --no-dev --no-scripts --no-autoloader --ignore-platform-reqs

COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: Application
FROM php:8.4-fpm-bullseye

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    supervisor \
    libzip-dev \
    libicu-dev \
    default-mysql-client

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache \
    && pecl install redis \
    && docker-php-ext-enable redis

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . .

# Copy built assets from Stage 1
COPY --from=assets-builder /app/public/build ./public/build

# Install dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev --no-scripts

# Setup configurations
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/app-php.ini
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

# Set permissions
RUN chmod +x /usr/local/bin/entrypoint.sh \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port 80
EXPOSE 80

# Entrypoint
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Stage 1: Node for asset building
FROM node:22-alpine as node

WORKDIR /app
COPY package*.json vite.config.js ./
COPY resources/ resources/
COPY public/ public/
RUN npm install && npm run build

# Stage 2: PHP for Laravel
FROM php:8.4-fpm

# System packages
RUN apt-get update && apt-get install -y \
    git curl unzip zip libonig-dev libxml2-dev libzip-dev libpng-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Copy Laravel app
COPY . .

# Copy built assets
COPY --from=node /app/public/build /var/www/public/build

# Install PHP deps
RUN composer install --no-dev --optimize-autoloader

# Laravel permissions
RUN chown -R www-data:www-data /var/www

CMD php artisan migrate --force && php artisan config:cache && php-fpm
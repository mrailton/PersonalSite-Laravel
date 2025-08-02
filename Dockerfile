FROM serversideup/php:8.4-unit

# Specify the NodeJS version
ARG NODE_VERSION=22

# Allow HTTP and HTTPS.
ENV SSL_MODE=mixed

# Switch to root so we can install NodeJS
# to the base image
USER root

# Install the intl extension for translations
RUN install-php-extensions exif

# Install NodeJS -> remove this if you do not need NodeJS
RUN apt-get update \
    && apt-get install -y bash \
    && curl -fsSL https://deb.nodesource.com/setup_$NODE_VERSION.x -o nodesource_setup.sh \
    && bash nodesource_setup.sh \
    && apt-get install -y nodejs \
    && apt-get install -y jpegoptim optipng pngquant gifsicle libavif-bin \
    && npm install -g svgo \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

# Switch back to non-privellage user www-data user
USER www-data

# Install production composer dependencies
COPY --chown=www-data:www-data composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-scripts --no-autoloader --no-progress --ignore-platform-reqs

# Install node dependencies -> remove this if you do not need NodeJS
COPY --chown=www-data:www-data package.json ./
RUN npm install --frozen-lockfile \
    && npm run build

# Copy the app files to the container
COPY --chown=www-data:www-data . .

# Autoload files
RUN composer dump-autoload --optimize

# Prepare the laravel app
RUN php /var/www/html/artisan storage:link
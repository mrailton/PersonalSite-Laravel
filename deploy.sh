#!/bin/bash

printf "\nInitiating deploy procedure:\n"

# Change into app dir
cd /var/www/personalsite

# Bring application down
php artisan down

# pull changes
git pull origin main

# Build assets
npm ci
npm run build
npm prune --omit=dev

# Install PHP packages
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# Restart PHP-FPM
sudo systemctl restart php8.4-fpm.service

# Run specific laravel commands to get application ready
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan migrate --force
php artisan queue:restart

# Bring the app back up
php artisan up

printf "\nDeploy completed.\n"

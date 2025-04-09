#!/usr/bin/env bash

mkdir -p database
touch database/database.sqlite
chmod 775 database
chmod 664 database/database.sqlite
chown -R www-data:www-data database

# Exécutez les migrations (optionnel, peut aussi être fait au runtime)
php artisan migrate
composer install --no-interaction --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
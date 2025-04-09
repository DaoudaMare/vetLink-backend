#!/usr/bin/env bash

# Exécutez les migrations (optionnel, peut aussi être fait au runtime)
php artisan migrate --force
composer install --no-interaction --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
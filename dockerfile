FROM php:8.2-apache

WORKDIR /var/www/html

# 1. Installer les dépendances système + SQLite
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    && docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Créer la structure pour SQLite avant de copier les fichiers
RUN mkdir -p database \
    && touch database/database.sqlite \
    && chown www-data:www-data database database/database.sqlite \
    && chmod 775 database \
    && chmod 664 database/database.sqlite

# 4. Copier UNIQUEMENT les fichiers nécessaires pour composer
COPY composer.json composer.lock ./

# 5. Installer les dépendances (sans scripts)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 6. Copier TOUS les fichiers de l'application
COPY . .

# 7. Exécuter les scripts composer
RUN composer run-script post-autoload-dump

# 8. Configurer les permissions
RUN chown -R www-data:www-data storage bootstrap/cache database \
    && find storage bootstrap/cache database -type d -exec chmod 775 {} \; \
    && find storage bootstrap/cache database -type f -exec chmod 664 {} \;

# 9. Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && a2enmod headers \
    && a2enmod ssl

# Configuration du virtual host
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    ErrorLog /var/log/apache2/error.log\n\
    CustomLog /var/log/apache2/access.log combined\n\
    <IfModule mod_headers.c>\n\
        Header always set Strict-Transport-Security \"max-age=63072000; includeSubDomains; preload\"\n\
    </IfModule>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 10. Préparation de l'application pour production
RUN php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

# 11. Exécuter les migrations et seeders (décommenter si nécessaire)
# RUN php artisan migrate --force --seed

EXPOSE 80
CMD ["apache2-foreground"]
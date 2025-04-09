FROM php:8.2-apache

WORKDIR /var/www/html

# 1. Installer les dépendances système avec libsqlite3-dev
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    sqlite3 \
    libsqlite3-dev \  
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Installer les extensions PHP en étapes séparées
RUN docker-php-ext-install pdo_mysql && \
    docker-php-ext-install pdo_sqlite && \
    docker-php-ext-install mbstring && \
    docker-php-ext-install exif && \
    docker-php-ext-install pcntl && \
    docker-php-ext-install bcmath && \
    docker-php-ext-install zip

# 3. Configurer et installer GD séparément
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# 4. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Créer la structure pour SQLite
RUN mkdir -p database && \
    touch database/database.sqlite && \
    chown www-data:www-data database database/database.sqlite && \
    chmod 775 database && \
    chmod 664 database/database.sqlite 

# 6. Copier les fichiers de dépendances
COPY composer.json composer.lock ./

# 7. Installer les dépendances en mode production
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 8. Copier le reste de l'application
COPY . .

# 9. Configurer les permissions
RUN chown -R www-data:www-data storage bootstrap/cache database && \
    find storage bootstrap/cache database -type d -exec chmod 775 {} \; && \
    find storage bootstrap/cache database -type f -exec chmod 664 {} \;

# 10. Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf && \
    a2enmod rewrite headers ssl && \
    a2dissite 000-default && \
    a2ensite 000-default

# Configuration du virtual host
RUN echo "<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        Options -Indexes +FollowSymLinks\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog /var/log/apache2/error.log\n\
    CustomLog /var/log/apache2/access.log combined\n\
    \n\
    <IfModule mod_headers.c>\n\
        Header always set Strict-Transport-Security \"max-age=63072000; includeSubDomains; preload\"\n\
    </IfModule>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 11. Optimisation Laravel
RUN php artisan config:clear && \
    php artisan config:cache && \
    php artisan route:clear && \
    php artisan route:cache && \
    php artisan view:clear && \
    php artisan view:cache

EXPOSE 80
CMD ["apache2-foreground"]
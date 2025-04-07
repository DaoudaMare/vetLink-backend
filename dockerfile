FROM php:8.2-apache

WORKDIR /var/www/html

# 1. Installer les dépendances système
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# 2. Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Copier UNIQUEMENT les fichiers nécessaires pour composer
COPY composer.json composer.lock ./

# 4. Désactiver les scripts pendant l'installation des dépendances
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 5. Copier TOUS les fichiers de l'application
COPY . .

# 6. Exécuter les scripts composer maintenant que tous les fichiers sont là
RUN composer run-script post-autoload-dump

# 7. Configurer les permissions
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# 8. Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && a2enmod headers

# Configuration du virtual host
RUN echo "<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

EXPOSE 80
CMD ["apache2-foreground"]
# Étape 1 : Image de base PHP avec Apache
FROM php:8.2-apache

# Étape 2 : Définir le répertoire de travail
WORKDIR /var/www/html

# Étape 3 : Installer les dépendances système
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

# Étape 4 : Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Étape 5 : Copier uniquement les fichiers nécessaires d'abord (optimisation du cache Docker)
COPY composer.json composer.lock ./

# Étape 6 : Installer les dépendances PHP (production)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Étape 7 : Copier le reste de l'application
COPY . .

# Étape 8 : Configurer les permissions (plus sécurisé que chmod 777)
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Étape 9 : Configuration Apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf \
    && a2enmod rewrite \
    && a2enmod headers

# Configuration du virtual host (sans fichier externe)
RUN echo "<VirtualHost *:80>\n\
    ServerAdmin webmaster@localhost\n\
    DocumentRoot /var/www/html/public\n\
    \n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
    \n\
    ErrorLog \${APACHE_LOG_DIR}/error.log\n\
    CustomLog \${APACHE_LOG_DIR}/access.log combined\n\
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Étape 10 : Exposer le port et démarrer Apache
EXPOSE 80
CMD ["apache2-foreground"]
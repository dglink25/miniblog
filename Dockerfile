# Étape 1 : image PHP avec extensions Laravel
FROM php:8.3-fpm

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo_mysql zip mbstring gd intl bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Définir le dossier de travail
WORKDIR /var/www

# Copier les fichiers
COPY . .

# Installer les dépendances PHP
RUN composer install --no-dev --optimize-autoloader

# Définir le user (optionnel)
RUN chown -R www-data:www-data /var/www

# Exposer le port 9000 (PHP-FPM)
EXPOSE 9000

# Lancer PHP-FPM
CMD ["php-fpm"]

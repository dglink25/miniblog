FROM php:8.2-fpm

# Installer dépendances système
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql bcmath gd zip

# Installer Composer
COPY --from=composer:2.5 /usr/bin/composer /usr/bin/composer

# Copier code
WORKDIR /var/www/html
COPY . .

# Installer dépendances Laravel
RUN composer install --optimize-autoloader --no-scripts --no-interaction

# Définir le port
EXPOSE 8000

# Commande de lancement
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]

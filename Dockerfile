# Dockerfile pour Laravel sur Render (serveur Apache)

FROM php:8.2-apache

# Installer extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    libpng-dev libjpeg62-turbo-dev libfreetype6-dev libonig-dev libxml2-dev libzip-dev unzip zip git libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# Copier tout le projet Laravel dans /var/www/html
COPY . /var/www/html

# Configurer Apache pour servir le dossier public/
RUN sed -i 's|/var/www/html|/var/www/html/public|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# Créer les dossiers nécessaires et leurs permissions
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Copier Composer depuis l’image officiel
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Installer les dépendances Laravel (sans exécuter les scripts)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Exposer le port attendu par Render et définir la variable PORT
EXPOSE 10000
ENV PORT=10000

# 🔧 Configurer les limites d’upload PHP à 100 Mo
RUN echo "upload_max_filesize=200M" > /usr/local/etc/php/conf.d/uploads.ini \
    && echo "post_max_size=210M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "memory_limit=512M" >> /usr/local/etc/php/conf.d/uploads.ini \
    && echo "max_execution_time=300" >> /usr/local/etc/php/conf.d/uploads.ini


# Créer le lien de stockage Laravel
RUN php artisan storage:link || true

RUN php artisan media:restore
RUN php artisan serve --host=0.0.0.0 --port=$PORT

# Lancer Apache en mode foreground
CMD ["apache2-foreground"]
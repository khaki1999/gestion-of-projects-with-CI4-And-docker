FROM php:8.2-fpm

# Définir le répertoire de travail
WORKDIR /var/www/html

# Installer les bibliothèques nécessaires
RUN apt-get update -y && apt-get install -y \
    libicu-dev \
    libmariadb-dev \
    unzip zip \
    zlib1g-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libzip-dev

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Installer les extensions PHP
RUN docker-php-ext-install gettext intl mysqli pdo pdo_mysql gd zip
RUN docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd

# Créer les répertoires nécessaires et configurer les permissions
RUN mkdir -p /var/www/html/writable/cache /var/www/html/public \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/writable \
    && chmod -R 755 /var/www/html/public

# Exposer le port 9000 pour PHP-FPM
EXPOSE 9000

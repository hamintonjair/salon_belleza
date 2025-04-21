# Dockerfile para CodeIgniter 4 en Apache con PHP 8.1
FROM php:8.1-apache

# Instalar dependencias del sistema y extensiones PHP
RUN apt-get update \
    && apt-get install -y \
       libicu-dev \
       libzip-dev unzip zlib1g-dev \
       libpng-dev libjpeg-dev libfreetype6-dev \
       libonig-dev \
       libpq-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install \
       intl mbstring pdo_mysql mysqli pdo_pgsql pgsql zip gd \
    && docker-php-ext-enable intl \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Instalar Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definir directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer y descargar dependencias
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Copiar todo el proyecto
COPY . .

# Ajustar permisos para directorio writable
RUN chown -R www-data:www-data writable \
    && chmod -R 775 writable

# Exponer puerto HTTP
EXPOSE 80

# Lanzar Apache en primer plano
CMD ["apache2-foreground"]

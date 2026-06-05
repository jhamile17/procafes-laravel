FROM php:8.2-cli

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Instalar extensiones PHP necesarias
RUN docker-php-ext-install pdo pdo_mysql zip

# Instalar Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

# Permisos Laravel
RUN chmod -R 777 storage bootstrap/cache

# Exponer puerto
EXPOSE 8080

# 🚀 EJECUTAR Laravel con artisan serve (LO QUE TE PIDEN)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

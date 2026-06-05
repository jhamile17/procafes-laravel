FROM php:8.2-cli

# DEPENDENCIAS
RUN apt-get update && apt-get install -y \
    git unzip zip curl \
    libzip-dev libpng-dev libonig-dev libxml2-dev

# NODEJS
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP EXTENSIONS
RUN docker-php-ext-install pdo pdo_mysql zip

# COMPOSER
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# BACKEND (cache correcto)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# FRONTEND (VITE)
COPY package*.json ./
RUN npm install --legacy-peer-deps

# copiar TODO el proyecto UNA sola vez
COPY . .

RUN npm run build

# PERMISOS
RUN chmod -R 777 storage bootstrap/cache

# debug opcional
RUN ls -la public/build

EXPOSE 8080

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

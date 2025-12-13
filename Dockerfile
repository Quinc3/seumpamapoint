FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    git curl zip unzip \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    oniguruma-dev libxml2-dev \
    postgresql-dev nodejs npm

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

# hapus file tidak PSR-4
RUN rm -rf app/Filament/Resources/Backup || true

# INSTALL TANPA SCRIPTS
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

EXPOSE 8080
CMD php -S 0.0.0.0:8080 -t public

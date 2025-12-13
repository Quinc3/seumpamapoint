FROM php:8.2-cli-alpine

# === SYSTEM & RUNTIME LIBS (WAJIB) ===
RUN apk add --no-cache \
    git curl zip unzip \
    icu-libs libzip \
    libpng libjpeg-turbo freetype \
    oniguruma libxml2 \
    postgresql-libs \
    nodejs npm

# === BUILD DEPS ===
RUN apk add --no-cache --virtual .build-deps \
    icu-dev libzip-dev \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    oniguruma-dev libxml2-dev \
    postgresql-dev

# === PHP EXTENSIONS ===
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
 && docker-php-ext-install \
    pdo pdo_mysql pdo_pgsql \
    mbstring bcmath gd intl zip

# === CLEAN BUILD DEPS ===
RUN apk del .build-deps

# === COMPOSER ===
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

# === PERMISSIONS ===
RUN mkdir -p storage/framework/{cache,sessions,views} bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache

# hapus file tidak PSR-4
RUN rm -rf app/Filament/Resources/Backup || true

# === COMPOSER INSTALL (AMAN) ===
RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

CMD ["sh", "-c", "php -S 0.0.0.0:${PORT} -t public"]
EXPOSE 8080


FROM php:8.3-fpm

# ----------------------------------------
# Install system packages
# ----------------------------------------
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libicu-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    && docker-php-ext-install intl zip gd pdo pdo_mysql

# ----------------------------------------
# Install Composer
# ----------------------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# ----------------------------------------
# Copy project
# ----------------------------------------
COPY . .

# ----------------------------------------
# Install PHP dependencies
# ----------------------------------------
RUN composer install --optimize-autoloader --no-interaction

# ----------------------------------------
# Laravel storage & bootstrap permissions
# ----------------------------------------
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

# ----------------------------------------
# Start Laravel
# ----------------------------------------
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]

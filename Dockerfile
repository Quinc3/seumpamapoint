FROM php:8.2-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    npm \
    nodejs \
    postgresql-dev \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev

# Install PHP extensions
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Buat folder storage dan cache sebelum install
RUN mkdir -p storage/framework/{cache,sessions,views}
RUN mkdir -p bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data storage bootstrap/cache

# Install dependencies - HAPUS file bermasalah dulu
RUN if [ -d "app/Filament/Resources/Backup" ]; then rm -rf app/Filament/Resources/Backup; fi

RUN composer install --no-dev --optimize-autoloader --no-interaction

# Generate key jika belum ada
RUN if [ ! -f ".env" ]; then cp .env.example .env && php artisan key:generate; fi

# Cache configuration
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Expose port 8080
EXPOSE 8080

# Healthcheck endpoint
HEALTHCHECK --interval=30s --timeout=3s --start-period=10s --retries=3 \
    CMD curl -f http://localhost:8080/health || exit 1

# Start command
CMD php artisan serve --host=0.0.0.0 --port=8080
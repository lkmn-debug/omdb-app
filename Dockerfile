FROM php:7.4-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

WORKDIR /app

COPY . .

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Fix permission
RUN chmod -R 775 storage bootstrap/cache

# Railway uses dynamic PORT
CMD php artisan config:clear && php artisan cache:clear && php artisan migrate --force && php -S 0.0.0.0:$PORT -t public

FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# FIX MPM ERROR
RUN a2dismod mpm_event
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Set document root
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Railway port fix
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Permission
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

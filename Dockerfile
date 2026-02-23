FROM php:7.4-apache

# Install extensions
RUN docker-php-ext-install pdo pdo_mysql

# Enable rewrite only (JANGAN sentuh MPM)
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Install composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-dev --optimize-autoloader

# Set document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Railway dynamic port fix
RUN sed -i 's/80/${PORT}/g' /etc/apache2/ports.conf
RUN sed -i 's/:80/:${PORT}/g' /etc/apache2/sites-available/000-default.conf

# Permission fix
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 storage bootstrap/cache

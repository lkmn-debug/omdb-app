FROM php:7.4-apache

# Install MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Remove default MPM configs
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load
RUN rm -f /etc/apache2/mods-enabled/mpm_event.conf

# Ensure prefork is enabled
RUN a2enmod mpm_prefork

# Enable rewrite
RUN a2enmod rewrite

WORKDIR /var/www/html

COPY . .

# Set document root to public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 755 /var/www/html/storage
RUN chmod -R 755 /var/www/html/bootstrap/cache

EXPOSE 80

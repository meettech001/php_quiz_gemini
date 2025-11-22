FROM php:8.2-apache

RUN a2enmod rewrite

RUN apt-get update && apt-get install -y \
    git curl zip unzip sqlite3 libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite

#Copy Apache config
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set permissions and enable rewrite
RUN chown -R www-data:www-data /var/www/html \
    && a2enmod rewrite
WORKDIR /var/www/html

COPY . .

RUN composer install

EXPOSE 80
CMD ["apache2-foreground"]

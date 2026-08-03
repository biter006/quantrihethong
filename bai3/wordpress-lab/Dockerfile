# Custom image cho WordPress — thêm extension Redis để dùng Object Cache
FROM wordpress:6.6.1-php8.3-apache

RUN pecl install redis \
    && docker-php-ext-enable redis

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

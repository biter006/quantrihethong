# Dockerfile — Bài lab 3
# Khác Bài lab 2 ở chỗ cài thêm extension redis để ứng dụng nói chuyện được với cache
FROM php:8.3-apache

# Extension cho MySQL
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Extension cho Redis (cài qua PECL vì không có sẵn trong image nền)
RUN pecl install redis \
    && docker-php-ext-enable redis

# Copy source code vào web root của Apache
COPY src/ /var/www/html/

# Phân quyền cho thư mục web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN a2enmod rewrite

EXPOSE 80

CMD ["apache2-foreground"]

# Dockerfile
FROM php:8.3-apache

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Copy source code vào web root của Apache
COPY src/ /var/www/html/

# Phân quyền cho thư mục web
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Bật mod rewrite (nếu sau này dùng .htaccess)
RUN a2enmod rewrite

# Expose port
EXPOSE 80

# Chạy Apache ở foreground
CMD ["apache2-foreground"]

# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi mysqli dan pdo_mysql agar bisa konek ke MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# Salin semua file proyek ke dalam container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Pastikan Apache pakai port 8080 (Railway default)
RUN sed -i 's/80/8080/g' /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Buka port 8080
EXPOSE 8080

# Jalankan Apache
CMD ["apache2-foreground"]

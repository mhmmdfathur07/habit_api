# Gunakan image PHP dengan Apache
FROM php:8.2-apache

# Install ekstensi MySQLi dan PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql

# Salin semua file proyek ke dalam container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Ubah konfigurasi Apache agar gunakan port dari $PORT (Railway)
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/000-default.conf

# Railway akan memberikan PORT lewat environment variable
EXPOSE 8080

# Jalankan Apache
CMD ["apache2-foreground"]

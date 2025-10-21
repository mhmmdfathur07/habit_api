# Gunakan image PHP yang sudah mendukung mysqli
FROM php:8.2-apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copy semua file proyek ke folder kerja di container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Buka port 8080 (Railway gunakan ini)
EXPOSE 8080

# Jalankan Apache
CMD ["apache2-foreground"]

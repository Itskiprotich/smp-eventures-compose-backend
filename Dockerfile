FROM php:8.0-apache

# Update apt-get cache and install required dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    zip \
    unzip

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli bcmath gd

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy the Laravel 8 application files to the Docker image
COPY . .

# Install the application dependencies using Composer
# RUN composer install --no-dev --optimize-autoloader
RUN composer update

# Set the ownership of the /var/www/html directory to the www-data user
RUN chown -R www-data:www-data /var/www/html

# Run database migrations
# RUN php artisan migrate --force

# Start the Apache web server in the foreground
CMD ["apache2-foreground"]

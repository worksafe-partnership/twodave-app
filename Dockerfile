FROM php:7.2-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -yq \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libpq-dev \
    zip \
    unzip \
    git \
    wget \
    gnupg \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install pdo pdo_pgsql gd

# Enable Apache mod_rewrite (required for Laravel routing)
RUN a2enmod rewrite

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# add google-chrome instance for PDF generation
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'
RUN apt-get update && apt-get install -yq google-chrome-stable

# Copy the current directory (where your Laravel app is) to the container's working directory
COPY . /var/www/html

# Set correct permissions for Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Install PHP dependencies using Composer
RUN composer install --no-scripts --no-autoloader

# Expose port 80 for HTTP access
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
FROM --platform=amd64 composer:1.4 as composer

WORKDIR /var/app
COPY . .
RUN composer install --no-dev --no-interaction

FROM --platform=amd64 node:12.0 as nodejs

WORKDIR /var/app
COPY --from=composer /var/app /var/app
RUN npm install --verbose
RUN npm run production

FROM --platform=amd64 php:7.2-apache

RUN apt-get update && \
    apt-get upgrade -yq && \
    apt-get install -yq gnupg wget zip unzip texlive-extra-utils

# install and enable mysql features
RUN docker-php-ext-install mysqli pdo pdo_mysql && \
    docker-php-ext-enable pdo_mysql

# add google-chrome instance for PDF generation
RUN wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add -
RUN sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'
RUN apt-get update && apt-get install -yq google-chrome-stable

# copy prepared files from nodejs stage
COPY --from=nodejs --chown=www-data:www-data /var/app /var/www/html

ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
RUN a2enmod rewrite
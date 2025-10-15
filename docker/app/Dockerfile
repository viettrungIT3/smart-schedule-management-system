FROM php:8.2-apache

# Cài extension cần thiết cho CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev zlib1g-dev libzip-dev libpng-dev git unzip \
 && docker-php-ext-install intl pdo pdo_mysql mysqli zip gd \
 && a2enmod rewrite headers

# Thiết lập DocumentRoot -> public
ARG APACHE_DOCUMENT_ROOT=/var/www/html/public
ENV APACHE_DOCUMENT_ROOT=${APACHE_DOCUMENT_ROOT}
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/000-default.conf \
 && sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Vhost riêng
COPY docker/app/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html



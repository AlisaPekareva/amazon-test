FROM php:7.4-fpm

# Установка необходимых расширений PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev zlib1g-dev libonig-dev \
    && docker-php-ext-install pdo pdo_mysql zip

# Установка Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

FROM php:8.2-fpm-alpine

RUN apk add --no-cache zip unzip libzip-dev \
    && docker-php-ext-install zip pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json ./

RUN composer install --no-dev --no-scripts

COPY . .
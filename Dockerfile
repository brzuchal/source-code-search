FROM php:7.2-apache

RUN docker-php-ext-install json
RUN a2enmod rewrite

WORKDIR /var/www
FROM php:7-apache

RUN apt-get update -y && apt-get upgrade -y
RUN apt-get install -y libxml2-dev libcurl4-openssl-dev
RUN docker-php-ext-install xml curl
COPY . /var/www/html

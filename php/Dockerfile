FROM php:8.1.9-apache
RUN a2enmod rewrite && \
    a2enmod authz_groupfile
RUN apt-get update -y && \
    apt-get upgrade -y
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
EXPOSE 80
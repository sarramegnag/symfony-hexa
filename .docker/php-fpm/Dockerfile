FROM php:8.1-fpm

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update -y && \
    apt-get install -y --no-install-recommends apt-utils git unzip

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && \
    sync && \
    install-php-extensions zip \
                           pdo_pgsql \
                           gd \
                           intl \
                           opcache && \
    usermod -u 1000 www-data

COPY conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

ENV COMPOSER_HOME /.composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
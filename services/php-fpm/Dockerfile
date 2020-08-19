FROM php:7.3-fpm

LABEL maintainer="Danilo Diogenes <zehdanilo@gmail.com>"

RUN ln -snf /usr/share/zoneinfo/America/Fortaleza /etc/localtime && echo America/Fortaleza > /etc/timezone

RUN docker-php-ext-install pdo_mysql
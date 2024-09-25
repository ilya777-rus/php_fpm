FROM php:8.1-fpm

RUN apt-get update && apt-get install -y curl git supervisor libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_mysql \
    && pecl install redis \
    && docker-php-ext-enable redis

WORKDIR /var/www/public_html

COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]


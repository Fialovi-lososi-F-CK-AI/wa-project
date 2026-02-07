FROM php:8.2-fpm-alpine AS builder

RUN apk add --no-cache \
    git \
    unzip \
    curl \
    bash \
    icu-dev \
    libzip-dev \
    oniguruma-dev \
    zlib-dev \
    postgresql-dev \
    autoconf \
    gcc \
    g++ \
    make

RUN docker-php-ext-install pdo pdo_pgsql intl zip opcache

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json composer.lock ./

RUN composer install --no-dev --optimize-autoloader --no-interaction

COPY . .

FROM php:8.2-fpm-alpine

RUN apk add --no-cache icu libzip zlib bash postgresql-client

COPY --from=builder /usr/local/lib/php/extensions/ /usr/local/lib/php/extensions/
COPY --from=builder /usr/local/etc/php/conf.d/ /usr/local/etc/php/conf.d/

WORKDIR /app
COPY --from=builder /app /app

RUN mkdir -p var/cache var/log \
    && chown -R www-data:www-data var

RUN { \
        echo 'opcache.enable=1'; \
        echo 'opcache.memory_consumption=128'; \
        echo 'opcache.interned_strings_buffer=8'; \
        echo 'opcache.max_accelerated_files=10000'; \
        echo 'opcache.validate_timestamps=0'; \
    } > /usr/local/etc/php/conf.d/opcache.ini

USER www-data

EXPOSE 9000

CMD ["php-fpm"]
FROM php:8.2-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.* symfony.* ./

RUN composer install --no-dev --optimize-autoloader

COPY . .

RUN mkdir -p var/cache var/log \
    && chmod +x bin/console

EXPOSE 9000

CMD ["php", "-S", "0.0.0.0:9000", "-t", "public"]

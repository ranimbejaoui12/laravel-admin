FROM php:8.1-cli

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git curl unzip libzip-dev zip \
    && docker-php-ext-install pdo pdo_mysql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

ENV COMPOSER_PROCESS_TIMEOUT=2000

RUN composer install --prefer-dist --no-interaction --no-progress --no-dev

RUN chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD php artisan serve --host=0.0.0.0 --port=10000

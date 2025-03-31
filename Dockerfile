FROM elrincondeisma/php-for-laravel:8.3.7

WORKDIR /app
COPY . .

RUN composer install
RUN composer require laravel/octane
RUN php artisan octane:install --server="swoole"

EXPOSE 8000
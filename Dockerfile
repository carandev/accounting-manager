FROM elrincondeisma/php-for-laravel:8.3.7

WORKDIR /app
COPY . .

# Instalar wait-for-it
RUN apt-get update && apt-get install -y wait-for-it

RUN composer install
RUN composer require laravel/octane
RUN mkdir -p /app/storage/logs

RUN php artisan key:generate

# Script para esperar a que la base de datos esté lista
COPY docker-entrypoint.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]

CMD php artisan octane:start --server="swoole" --host="0.0.0.0"
EXPOSE 8000
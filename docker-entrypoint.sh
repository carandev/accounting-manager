#!/bin/bash

# Esperar a que MySQL esté disponible
echo "Esperando a que MySQL esté disponible..."
wait-for-it mysql:3306 -t 60

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --seed --force

# Instalar Octane
echo "Instalando Octane..."
php artisan octane:install --server="swoole"

# Ejecutar el comando original
exec "$@" 
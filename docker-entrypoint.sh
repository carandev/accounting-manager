#!/bin/bash

# Función para esperar a que un servicio esté disponible
wait_for_service() {
    local host="$1"
    local port="$2"
    local timeout="${3:-60}"
    local start_time=$(date +%s)
    
    echo "Esperando a que $host:$port esté disponible..."
    
    while true; do
        if nc -z "$host" "$port"; then
            echo "$host:$port está disponible"
            return 0
        fi
        
        local current_time=$(date +%s)
        local elapsed=$((current_time - start_time))
        
        if [ $elapsed -ge $timeout ]; then
            echo "Timeout esperando a que $host:$port esté disponible"
            return 1
        fi
        
        sleep 1
    done
}

# Esperar a que MySQL esté disponible
wait_for_service mysql 3306

# Ejecutar migraciones
echo "Ejecutando migraciones..."
php artisan migrate --seed --force

# Instalar Octane
echo "Instalando Octane..."
php artisan octane:install --server="swoole"

# Ejecutar el comando original
exec "$@" 
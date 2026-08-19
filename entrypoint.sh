#!/bin/sh
set -e

echo "Ajustando permisos de almacenamiento..."
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Limpiar caché para que Render lea las variables de entorno frescas
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Enlace simbólico para storage (si se usa)
php artisan storage:link --force || true

# Ejecutar migraciones automáticamente de forma segura (SIN borrar)
echo "Ejecutando migraciones (seguro)..."
php artisan migrate --force

# Iniciar PHP-FPM en segundo plano
php-fpm -D

# Arrancar Nginx EN PRIMER PLANO (esto abre el puerto 80 para que Render lo detecte)
echo "Iniciando Nginx..."
nginx -g 'daemon off;'

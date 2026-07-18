#!/bin/sh
set -e

echo "Ajustando permisos de almacenamiento..."
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Verificar que la variable DATABASE_URL esté definida (para depuración)
if [ -n "$DATABASE_URL" ]; then
    echo "DATABASE_URL detectada: $(echo $DATABASE_URL | sed 's/:[^:]*@/:***@/')"
else
    echo "ADVERTENCIA: DATABASE_URL no está definida."
fi

# Forzar la optimización de caché en producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Enlace simbólico para storage (si se usa)
php artisan storage:link --force || true

# Ejecutar migraciones automáticamente
echo "Reconstruyendo base de datos..."

php artisan migrate:fresh --force

php artisan db:seed --force

# Iniciar PHP-FPM en segundo plano
php-fpm -D



# Arrancar Nginx EN PRIMER PLANO (esto abre el puerto 80 para que Render lo detecte)
echo "Iniciando Nginx..."
nginx -g 'daemon off;'

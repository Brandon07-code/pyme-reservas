<?php
// Este script permite resetear la base de datos de manera manual y segura en Render
// Se ejecuta en segundo plano para evitar el timeout de 100 segundos de Render

$logFile = '/tmp/seeder.log';
$command = 'cd /var/www/html && php artisan migrate:fresh --seed > ' . $logFile . ' 2>&1 &';

exec($command);

echo "<html><body style='font-family: sans-serif; text-align: center; margin-top: 100px;'>";
echo "<h1>🚀 Reconstrucción Iniciada</h1>";
echo "<p>El servidor ha comenzado a borrar y re-sembrar la base de datos completa (6 meses de historial).</p>";
echo "<p>Este proceso toma alrededor de <strong>2 a 3 minutos</strong> en completarse.</p>";
echo "<p>Por favor, cierra esta pestaña y ve al panel de la barbería. Si no ves los datos completos, espera un par de minutos más y recarga.</p>";
echo "</body></html>";

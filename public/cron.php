<?php
/**
 * CRON JOB INDEPENDIENTE - NO PASA POR LARAVEL
 * 
 * Este script se conecta directamente a PostgreSQL sin cargar
 * el framework Laravel, evitando errores de "Response data too big"
 * causados por el arranque en frío (cold start) de Render.
 * 
 * URL: https://pyme-reservas.onrender.com/cron.php?token=jym-seguro-2026
 */

// Forzar salida JSON mínima pase lo que pase
header('Content-Type: application/json');

// Seguridad: verificar token
if (($_GET['token'] ?? '') !== 'jym-seguro-2026') {
    echo json_encode(['error' => 'Acceso denegado']);
    exit;
}

try {
    // Leer las variables de entorno que Render inyecta
    // Render usa DATABASE_URL o variables DB_* individuales
    $databaseUrl = getenv('DATABASE_URL');
    
    if ($databaseUrl) {
        // Parsear DATABASE_URL (formato: postgres://user:pass@host:port/dbname)
        $parts = parse_url($databaseUrl);
        $host = $parts['host'] ?? '';
        $port = $parts['port'] ?? 5432;
        $dbname = ltrim($parts['path'] ?? '', '/');
        $user = $parts['user'] ?? '';
        $pass = $parts['pass'] ?? '';
    } else {
        // Fallback a variables individuales
        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: 5432;
        $dbname = getenv('DB_DATABASE') ?: 'pyme_reservas';
        $user = getenv('DB_USERNAME') ?: 'root';
        $pass = getenv('DB_PASSWORD') ?: '';
    }
    
    $dsn = "pgsql:host={$host};port={$port};dbname={$dbname}";
    
    // Conexión con timeout de 5 segundos para no quedarnos colgados
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 5,
    ]);
    
    $ahora = date('Y-m-d H:i:s');
    $hoy = date('Y-m-d');
    $horaActual = date('H:i:s');
    
    // Pendientes de días anteriores -> No Asistió
    $stmt1 = $pdo->prepare("
        UPDATE reservations 
        SET estado = 'no_asistio', updated_at = :ahora 
        WHERE estado = 'pendiente' 
        AND fecha < :hoy
    ");
    $stmt1->execute(['ahora' => $ahora, 'hoy' => $hoy]);
    $noAsistioPasadas = $stmt1->rowCount();
    
    // Pendientes de hoy cuya hora ya pasó -> No Asistió
    $stmt2 = $pdo->prepare("
        UPDATE reservations 
        SET estado = 'no_asistio', updated_at = :ahora 
        WHERE estado = 'pendiente' 
        AND fecha = :hoy 
        AND hora_fin < :hora
    ");
    $stmt2->execute(['ahora' => $ahora, 'hoy' => $hoy, 'hora' => $horaActual]);
    $noAsistioHoy = $stmt2->rowCount();
    
    // Confirmadas de días anteriores -> Completada
    $stmt3 = $pdo->prepare("
        UPDATE reservations 
        SET estado = 'completada', updated_at = :ahora 
        WHERE estado = 'confirmada' 
        AND fecha < :hoy
    ");
    $stmt3->execute(['ahora' => $ahora, 'hoy' => $hoy]);
    $completadasPasadas = $stmt3->rowCount();
    
    // Confirmadas de hoy cuya hora ya pasó -> Completada
    $stmt4 = $pdo->prepare("
        UPDATE reservations 
        SET estado = 'completada', updated_at = :ahora 
        WHERE estado = 'confirmada' 
        AND fecha = :hoy 
        AND hora_fin < :hora
    ");
    $stmt4->execute(['ahora' => $ahora, 'hoy' => $hoy, 'hora' => $horaActual]);
    $completadasHoy = $stmt4->rowCount();
    
    $totalNoAsistio = $noAsistioPasadas + $noAsistioHoy;
    $totalCompletadas = $completadasPasadas + $completadasHoy;
    
    echo json_encode([
        'status' => 'success',
        'no_asistio' => $totalNoAsistio,
        'completadas' => $totalCompletadas,
        'timestamp' => $ahora
    ]);
    
} catch (Throwable $e) {
    // Incluso si la DB está muerta, la respuesta será TINY (< 200 bytes)
    echo json_encode([
        'status' => 'db_timeout',
        'msg' => 'BD no disponible, reintentando en el proximo ciclo'
    ]);
}

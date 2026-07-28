<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController;

// --- RUTAS PÚBLICAS ---
Route::post('login', [AuthController::class, 'login']);

// --- RUTAS PROTEGIDAS POR JWT ---
Route::middleware('auth:api')->group(function () {
    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    // API CRUD de Reservas
    Route::apiResource('reservations', ReservationController::class)->only(['index', 'store', 'destroy']);

    // Ejemplo: Ruta exclusiva para Admin usando el middleware VerificarRol
    Route::middleware('rol:1')->group(function () {
        Route::get('/admin/kpis', function() {
            return response()->json(['message' => 'Endpoint financiero top secret (Solo Admin)']);
        });
    });
});

// ==============================================================
// RUTA PARA CRON JOB EXTERNO (cron-job.org)
// ==============================================================
Route::get('/cron/marcar-citas-vencidas', function (\Illuminate\Http\Request $request) {
    // Verificación de seguridad simple
    if ($request->query('token') !== 'jym-seguro-2026') {
        return response()->json(['error' => 'Acceso denegado'], 403);
    }

    try {
        $exitCode = \Illuminate\Support\Facades\Artisan::call('reservas:marcar-vencidas');
        $output = \Illuminate\Support\Facades\Artisan::output();
        
        if ($exitCode !== 0) {
            \Illuminate\Support\Facades\Log::error('Cron job failed', ['output' => $output]);
        }

        return response()->json([
            'status' => $exitCode === 0 ? 'success' : 'error',
            'message' => 'Comando ejecutado con código ' . $exitCode,
            'output' => substr($output, 0, 200) . (strlen($output) > 200 ? '... [Truncated]' : '')
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => substr($e->getMessage(), 0, 300)
        ], 500);
    }
});
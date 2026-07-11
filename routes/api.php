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
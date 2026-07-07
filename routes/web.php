<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;

// 1. Grupo de rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    
    // Dashboard principal
    Route::get('/', DashboardController::class)->name('dashboard');

    // Módulos del sistema
    Route::resource('usuarios', UserController::class);
    Route::resource('empleados', EmployeeController::class);
    Route::resource('clientes', ClientController::class);
    Route::resource('servicios', ServiceController::class);
    Route::resource('productos', ProductController::class);
    Route::resource('reservas', ReservationController::class);

});

// 2. Cargar las rutas de Login/Register generadas por Breeze
require __DIR__.'/auth.php';
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;

Route::middleware(['auth'])->group(function () {
    
    // Todos los logueados (Admin y Empleados) pueden ver esto
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::resource('reservas', ReservationController::class);

    // SOLO Administradores pueden entrar aquí
    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
        Route::resource('empleados', EmployeeController::class);
        Route::resource('clientes', ClientController::class);
        Route::resource('servicios', ServiceController::class);
        Route::resource('productos', ProductController::class);
    });
});

require __DIR__.'/auth.php';
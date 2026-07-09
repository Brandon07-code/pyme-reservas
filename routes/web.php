<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ProfileController;

Route::middleware(['auth'])->group(function () {
    
    Route::get('/', DashboardController::class)->name('dashboard');
    // Rutas de gestión de Horarios para el Admin
        Route::get('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'edit'])->name('empleados.horarios.edit');
        Route::put('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('empleados.horarios.update');
    // PERFIL: Todos los logueados pueden editar sus propios datos
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::patch('/reservas/{reserva}/completar', [ReservationController::class, 'markAsCompleted'])->name('reservas.completar');
    Route::resource('reservas', ReservationController::class);

    // SOLO Administradores
    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
        Route::resource('empleados', EmployeeController::class);
        Route::resource('clientes', ClientController::class);
        Route::resource('servicios', ServiceController::class);
        Route::resource('productos', ProductController::class);
    });
});

require __DIR__.'/auth.php';
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
    
    // REDIRECCIÓN TEMPORAL DEL PORTAL CLIENTE
    Route::get('/mi-portal', function() {
        return "Bienvenido al Portal Cliente de PYME Reservas. Esta interfaz está en construcción.";
    })->name('portal.index');

    // PANEL ADMINISTRATIVO
    Route::get('/', DashboardController::class)->name('dashboard');
    
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::patch('/reservas/{reserva}/completar', [ReservationController::class, 'markAsCompleted'])->name('reservas.completar');
    Route::resource('reservas', ReservationController::class);

    Route::middleware(['admin'])->group(function () {
        Route::resource('usuarios', UserController::class);
        Route::get('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'edit'])->name('empleados.horarios.edit');
        Route::put('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('empleados.horarios.update');
        Route::resource('empleados', EmployeeController::class);
        Route::resource('clientes', ClientController::class);
        Route::resource('servicios', ServiceController::class);
        Route::resource('productos', ProductController::class);
    });
});

require __DIR__.'/auth.php';
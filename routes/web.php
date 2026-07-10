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

// ==============================================================
// RUTAS COMUNES (LOGUEADOS)
// ==============================================================
Route::middleware(['auth'])->group(function () {
    
    // Perfil: Todos (Admin, Empleado, Cliente) pueden editar su perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
 
       Route::middleware(['admin:3'])->group(function () {
        
        // La vitrina comercial
        Route::get('/mi-portal', [\App\Http\Controllers\PortalController::class, 'index'])->name('portal.index');
        
        // La raíz para los clientes los enviará a su portal
        Route::get('/', function() {
            if (auth()->user()->role_id == 3) {
                return redirect()->route('portal.index');
            }
            return redirect()->route('dashboard');
        });
    });
 
    Route::middleware(['admin:1,2'])->group(function () {
        
        // La raíz para empleados/admin es el Dashboard
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        
        // Módulo de Reservas Operativo
        Route::patch('/reservas/{reserva}/completar', [ReservationController::class, 'markAsCompleted'])->name('reservas.completar');
        Route::resource('reservas', ReservationController::class);
    });

    // ==============================================================
    // RUTAS ADMINISTRATIVAS (Solo Admin(1))
    // ==============================================================
    Route::middleware(['admin:1'])->group(function () {
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
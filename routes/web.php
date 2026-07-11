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
use App\Http\Controllers\PortalController;
// ==============================================================
// RUTAS COMUNES (LOGUEADOS)
// ==============================================================
Route::middleware(['auth'])->group(function () {
    
    // Perfil: Todos (Admin, Empleado, Cliente) pueden editar su perfil
    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
 // ==============================================================
    // RUTAS CLIENTES (Solo Rol 3)
    // ==============================================================
    Route::middleware(['admin:3'])->group(function () {
        
        // Vitrina Comercial
        Route::get('/mi-portal', [PortalController::class, 'index'])->name('portal.index');
        
        // Asistente de Agendamiento
        Route::get('/mi-portal/agendar', [PortalController::class, 'agendar'])->name('portal.agendar');
        Route::post('/mi-portal/agendar/disponibilidad', [PortalController::class, 'getDisponibilidad'])->name('portal.disponibilidad');
        Route::post('/mi-portal/agendar', [PortalController::class, 'storeReserva'])->name('portal.store');

        // NUEVO: Historial de Citas
        Route::get('/mi-portal/mis-citas', [PortalController::class, 'misCitas'])->name('portal.citas');
        Route::patch('/mi-portal/mis-citas/{reserva}/cancelar', [PortalController::class, 'cancelarCita'])->name('portal.citas.cancelar');

        // Redirección Raíz
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
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
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ExternalPostController;

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

        // Historial de Citas
        Route::get('/mi-portal/mis-citas', [PortalController::class, 'misCitas'])->name('portal.citas');
        Route::patch('/mi-portal/mis-citas/{reserva}/cancelar', [PortalController::class, 'cancelarCita'])->name('portal.citas.cancelar');

        // Carrito de Compras y Checkout
        Route::get('/mi-portal/carrito', [CartController::class, 'index'])->name('portal.cart.index');
        Route::post('/mi-portal/carrito/add', [CartController::class, 'add'])->name('portal.cart.add');
        Route::post('/mi-portal/carrito/remove', [CartController::class, 'remove'])->name('portal.cart.remove');
        Route::post('/mi-portal/carrito/checkout', [CartController::class, 'checkout'])->name('portal.cart.checkout'); // FIX DEL ERROR 500

        // Redirección Raíz
        Route::get('/', function() {
            if (auth()->user()->role_id == 3) {
                return redirect()->route('portal.index');
            }
            return redirect()->route('dashboard');
        });
    });

    // ============================================================== 
    // RUTAS OPERATIVAS (Admin 1, Empleado 2) 
    // ==============================================================
    Route::middleware(['admin:1,2'])->group(function () {
    
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        
        Route::patch('/reservas/{reserva}/completar', [ReservationController::class, 'markAsCompleted'])->name('reservas.completar');
        Route::resource('reservas', ReservationController::class);

        // Notificaciones (Leer)
        Route::post('/notificaciones/leer', function() {
            auth()->user()->unreadNotifications->markAsRead();
            return redirect()->back();
        })->name('notificaciones.leer');
    });

    // ==============================================================
    // RUTAS ADMINISTRATIVAS (Solo Admin(1))
    // ==============================================================
    Route::middleware(['admin:1'])->group(function () {
        Route::resource('usuarios', UserController::class);
        
        Route::get('/integracion/posts', [ExternalPostController::class, 'index'])->name('posts.index');
        
        Route::get('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'edit'])->name('empleados.horarios.edit');
        Route::put('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('empleados.horarios.update');
        Route::resource('empleados', EmployeeController::class);
        
        Route::resource('clientes', ClientController::class);
        Route::resource('servicios', ServiceController::class);
        Route::resource('productos', ProductController::class);

        // Módulo de Pedidos de Tienda
        Route::resource('pedidos', OrderController::class)->only(['index', 'update'])->names('orders');
    });

});

require __DIR__.'/auth.php';
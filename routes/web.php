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
use App\Http\Controllers\OtpVerificationController;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->role_id == 3
            ? redirect()->route('portal.index')
            : redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

// RUTINA TEMPORAL PARA ARREGLOS EN PRODUCCIÓN (RENDER)
Route::get('/fix-clean-user', function () {
    $msg = "";
    
    // 1. Borrar usuario bugeado
    $user = \App\Models\User::where('email', 'brandongiraldo8@gmail.com')->first();
    if ($user) {
        if ($user->employee) {
            $user->employee->reservations()->delete();
            $user->employee->delete();
        }
        if ($user->client) {
            $user->client->reservations()->delete();
            $user->client->orders()->delete();
            $user->client->delete();
        }
        $user->delete();
        $msg .= "Usuario brandongiraldo8@gmail.com y su historial eliminado. <br>";
    }

    // 2. Migrar admin actual al dominio corporativo
    $admin = \App\Models\User::where('email', 'admin@brandon.local')->first();
    if ($admin) {
        $admin->update(['email' => 'admin@pymereservas.com']);
        // También actualizar el email en la tabla employees si existe
        if ($admin->employee) {
            $admin->employee->update(['email' => 'admin@pymereservas.com']);
        }
        $msg .= "Admin actualizado a admin@pymereservas.com (Tendrás que iniciar sesión con este nuevo correo).<br>";
    }
    
    return $msg ?: "Todo está limpio. No se requirieron cambios.";
});

// ============================================================== 
// RUTAS DE VERIFICACIÓN OTP (Fuera del middleware auth)
// ==============================================================
Route::get('/otp-verify', [OtpVerificationController::class, 'show'])->name('otp.verify');
Route::post('/otp-verify', [OtpVerificationController::class, 'verify'])->name('otp.verify.post');
Route::post('/otp-resend', [OtpVerificationController::class, 'resend'])->name('otp.resend');

Route::middleware(['auth'])->group(function () {

    Route::get('/perfil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/perfil', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/perfil', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================================== 
    // RUTAS CLIENTES (Solo Rol 3) 
    // ==============================================================
    Route::middleware(['admin:3'])->group(function () {

        Route::get('/mi-portal', [PortalController::class, 'index'])->name('portal.index');
        
        Route::get('/mi-portal/agendar', [PortalController::class, 'agendar'])->name('portal.agendar');
        Route::post('/mi-portal/agendar/disponibilidad', [PortalController::class, 'getDisponibilidad'])->name('portal.disponibilidad');
        Route::post('/mi-portal/agendar', [PortalController::class, 'storeReserva'])->name('portal.store');

        Route::get('/mi-portal/mis-citas', [PortalController::class, 'misCitas'])->name('portal.citas');
        Route::patch('/mi-portal/mis-citas/{reserva}/cancelar', [PortalController::class, 'cancelarCita'])->name('portal.citas.cancelar');

        Route::get('/mi-portal/carrito', [CartController::class, 'index'])->name('portal.cart.index');
        Route::post('/mi-portal/carrito/add', [CartController::class, 'add'])->name('portal.cart.add');
        Route::post('/mi-portal/carrito/remove', [CartController::class, 'remove'])->name('portal.cart.remove');
        Route::post('/mi-portal/carrito/checkout', [CartController::class, 'checkout'])->name('portal.cart.checkout');

        Route::get('/mi-portal/mis-pedidos', [PortalController::class, 'misPedidos'])->name('portal.pedidos');
        Route::patch('/mi-portal/mis-pedidos/{order}/cancelar', [PortalController::class, 'cancelarPedido'])->name('portal.pedidos.cancelar');
    });

    // ============================================================== 

    // RUTAS OPERATIVAS (Admin 1, Empleado 2) 
    // ==============================================================
    Route::middleware(['admin:1,2'])->group(function () {
    
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        
        Route::get('/reservas/export-pdf', [ReservationController::class, 'exportPdf'])->name('reservas.export-pdf');
        Route::patch('/reservas/{reserva}/completar', [ReservationController::class, 'markAsCompleted'])->name('reservas.completar');
        Route::patch('/reservas/{reserva}/confirmar', [ReservationController::class, 'markAsConfirmed'])->name('reservas.confirmar');
        Route::resource('reservas', ReservationController::class);
        
        Route::get('/dashboard/citas-hoy', [App\Http\Controllers\DashboardController::class, 'citasHoyAjax'])->name('dashboard.citas_hoy_ajax');
    });

    // ==============================================================
    // RUTAS COMPARTIDAS (Notificaciones)
    // ==============================================================
    Route::get('/notificaciones/check', function() {
        $notificaciones = auth()->user()->unreadNotifications;
        $html = view('partials.notifications-list', compact('notificaciones'))->render();
        return response()->json([
            'count' => $notificaciones->count(),
            'html' => $html
        ]);
    })->name('notificaciones.check');

    Route::post('/notificaciones/leer', function() {
        auth()->user()->unreadNotifications->markAsRead();
        return redirect()->back();
    })->name('notificaciones.leer');

    // ==============================================================
    // RUTAS ADMINISTRATIVAS (Solo Admin(1))
    // ==============================================================
    Route::middleware(['admin:1'])->group(function () {
        Route::get('/usuarios/export-pdf', [UserController::class, 'exportPdf'])->name('usuarios.export-pdf');
        Route::resource('usuarios', UserController::class);

        Route::get('/integracion/posts', [ExternalPostController::class, 'index'])->name('posts.index');

        Route::get('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'edit'])->name('empleados.horarios.edit');
        Route::put('empleados/{empleado}/horarios', [\App\Http\Controllers\ScheduleController::class, 'update'])->name('empleados.horarios.update');
        Route::get('/empleados/export-pdf', [EmployeeController::class, 'exportPdf'])->name('empleados.export-pdf');
        Route::resource('empleados', EmployeeController::class);

        Route::get('/clientes/export-pdf', [ClientController::class, 'exportPdf'])->name('clientes.export-pdf');
        Route::resource('clientes', ClientController::class);

        Route::get('/servicios/export-pdf', [ServiceController::class, 'exportPdf'])->name('servicios.export-pdf');
        Route::resource('servicios', ServiceController::class);

        Route::get('/productos/export-pdf', [ProductController::class, 'exportPdf'])->name('productos.export-pdf');
        Route::resource('productos', ProductController::class);

        Route::get('/pedidos/export-pdf', [OrderController::class, 'exportPdf'])->name('orders.export-pdf');
        Route::resource('pedidos', OrderController::class)->only(['index', 'update'])->names('orders');
    });

});

require __DIR__.'/auth.php';



Route::get('/debug-email', function() {
    $reserva = \App\Models\Reservation::first();
    return (new \App\Mail\BarberReservationNotification($reserva))->render();
});
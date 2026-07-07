<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReservationController;

Route::get('/', DashboardController::class);

// Route::resource crea automáticamente las rutas para index, create, store, show, edit, update, destroy
Route::resource('usuarios', UserController::class);
Route::resource('empleados', EmployeeController::class);
Route::resource('clientes', ClientController::class);
Route::resource('servicios', ServiceController::class);
Route::resource('productos', ProductController::class);
Route::resource('reservas', ReservationController::class);

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
Route::get('/usuarios', [UserController::class, 'index']);
Route::get('/empleados', [EmployeeController::class, 'index']);
Route::get('/clientes', [ClientController::class, 'index']);
Route::get('/servicios', [ServiceController::class, 'index']);
Route::get('/productos', [ProductController::class, 'index']);
Route::get('/reservas', [ReservationController::class, 'index']);
Route::get('/', function () {
    return view('welcome');
});

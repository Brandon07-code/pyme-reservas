<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    const STOCK_MINIMO = 5;

    public function __invoke(Request $request)
    {
        $hoy = Carbon::today();
        $manana = Carbon::tomorrow();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        $usuario = Auth::user();
        $esAdmin = $usuario->role_id == 1;

        $queryActivas = Reservation::activas();
        if (!$esAdmin && $usuario->employee) {
            $queryActivas->where('employee_id', $usuario->employee->id);
        }

        $citasHoy = (clone $queryActivas)->whereDate('fecha', $hoy)->count();
        $citasManana = (clone $queryActivas)->whereDate('fecha', $manana)->count();

        $ingresosMes = 0;
        $clientesNuevos = 0;
        $inventarioCritico = collect();
        $topBarberos = collect();
        $topServicios = collect();
        $labelsGrafica = [];
        $datosGrafica = [];
        $donutData = [];
        $misCortesMes = 0;

        if ($esAdmin) {
            $ingresosMes = Reservation::completadas()->delMes($mesActual, $anioActual)->sum('total');
            $clientesNuevos = Client::whereMonth('created_at', $mesActual)->whereYear('created_at', $anioActual)->count();
            $inventarioCritico = Product::where('estado', 1)->where('stock_actual', '<=', self::STOCK_MINIMO)->get();
            $topBarberos = Employee::with('user')->where('estado', 1)->withCount(['reservations' => function ($query) use ($mesActual, $anioActual) {
                $query->completadas()->delMes($mesActual, $anioActual);
            }])->orderByDesc('reservations_count')->take(3)->get();
            $topServicios = Service::where('estado', 1)->withCount(['reservations' => function ($query) use ($mesActual, $anioActual) {
                $query->completadas()->delMes($mesActual, $anioActual);
            }])->orderByDesc('reservations_count')->take(5)->get();

            for ($i = 5; $i >= 0; $i--) {
                $mes = Carbon::now()->subMonths($i);
                $ingresoMes = Reservation::completadas()->delMes($mes->month, $mes->year)->sum('total');
                $labelsGrafica[] = ucfirst($mes->translatedFormat('F'));
                $datosGrafica[] = $ingresoMes;
            }

            $donutData = [
                Reservation::delMes($mesActual, $anioActual)->where('estado', 'pendiente')->count(),
                Reservation::delMes($mesActual, $anioActual)->where('estado', 'confirmada')->count(),
                Reservation::delMes($mesActual, $anioActual)->where('estado', 'completada')->count(),
                Reservation::delMes($mesActual, $anioActual)->where('estado', 'cancelada')->count(),
            ];
        } else {
            if ($usuario->employee) {
                $misCortesMes = Reservation::where('employee_id', $usuario->employee->id)->completadas()->delMes($mesActual, $anioActual)->count();
            }
        }

        $queryProximas = Reservation::with(['client', 'employee.user'])->activas()->whereDate('fecha', $hoy)->orderBy('hora_inicio', 'asc')->take(5);
        if (!$esAdmin && $usuario->employee) {
            $queryProximas->where('employee_id', $usuario->employee->id);
        }
        $proximasCitas = $queryProximas->get();

        $fechaHoyFmt = $hoy->format('Y-m-d');
        $fechaMananaFmt = $manana->format('Y-m-d');

        return view('dashboard.index', compact(
            'esAdmin', 'citasHoy', 'citasManana', 'ingresosMes', 'clientesNuevos', 
            'proximasCitas', 'inventarioCritico', 'topBarberos', 'topServicios', 
            'labelsGrafica', 'datosGrafica', 'misCortesMes', 'fechaHoyFmt', 'fechaMananaFmt', 'donutData'
        ));
    }

    public function citasHoyAjax(Request $request)
    {
        $hoy = Carbon::today();
        $usuario = Auth::user();
        $esAdmin = $usuario->role_id == 1;

        $queryProximas = Reservation::with(['client', 'employee.user'])->activas()->whereDate('fecha', $hoy)->orderBy('hora_inicio', 'asc')->take(5);
        if (!$esAdmin && $usuario->employee) {
            $queryProximas->where('employee_id', $usuario->employee->id);
        }
        $proximasCitas = $queryProximas->get();

        return view('dashboard.partials.citas-hoy-list', compact('proximasCitas', 'esAdmin'))->render();
    }
}
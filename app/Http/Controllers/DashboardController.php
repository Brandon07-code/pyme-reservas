<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Product;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    const STOCK_MINIMO = 5;

    public function __invoke(Request $request)
    {
        // 1. Contexto Temporal
        $hoy = Carbon::today();
        $manana = Carbon::tomorrow();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // 2. Tarjetas KPI (Ampliadas)
        $citasHoy = Reservation::activas()->whereDate('fecha', $hoy)->count();
        $citasCompletadasHoy = Reservation::completadas()->whereDate('fecha', $hoy)->count();
        $citasCanceladasHoy = Reservation::where('estado', 'cancelada')->whereDate('fecha', $hoy)->count();
        $citasManana = Reservation::activas()->whereDate('fecha', $manana)->count();

        $ingresosMes = Reservation::completadas()
            ->delMes($mesActual, $anioActual)
            ->sum('total');
            
        $clientesNuevos = Client::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->count();

        // 3. Panel Operativo
        $proximasCitas = Reservation::with(['client', 'employee.user'])
            ->activas()
            ->whereDate('fecha', $hoy)
            ->orderBy('hora_inicio', 'asc')
            ->take(5)
            ->get();

        $inventarioCritico = Product::where('estado', 1)
            ->where('stock_actual', '<=', self::STOCK_MINIMO)
            ->get();

        // 4. Analíticas del Mes
        $topBarberos = Employee::with('user')
            ->where('estado', 1)
            ->withCount(['reservations' => function ($query) use ($mesActual, $anioActual) {
                $query->completadas()->delMes($mesActual, $anioActual);
            }])
            ->orderByDesc('reservations_count')
            ->take(3)
            ->get();

        $topServicios = Service::where('estado', 1)
            ->withCount(['reservations' => function ($query) use ($mesActual, $anioActual) {
                $query->completadas()->delMes($mesActual, $anioActual);
            }])
            ->orderByDesc('reservations_count')
            ->take(5)
            ->get();

        // 5. Datos para la Gráfica
        $labelsGrafica = [];
        $datosGrafica = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            
            $ingresoMes = Reservation::completadas()
                ->delMes($mes->month, $mes->year)
                ->sum('total');

            $labelsGrafica[] = ucfirst($mes->translatedFormat('F'));
            $datosGrafica[] = $ingresoMes;
        }

        return view('dashboard.index', compact(
            'citasHoy', 'citasCompletadasHoy', 'citasCanceladasHoy', 'citasManana',
            'ingresosMes', 'clientesNuevos', 'proximasCitas', 'inventarioCritico', 
            'topBarberos', 'topServicios', 'labelsGrafica', 'datosGrafica'
        ));
    }
}
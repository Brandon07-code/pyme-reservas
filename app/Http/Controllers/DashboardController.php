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
    // Evitamos "Números Mágicos"
    const STOCK_MINIMO = 5;

    public function __invoke(Request $request)
    {
        // 1. Contexto Temporal
        $hoy = Carbon::today();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;

        // 2. Tarjetas KPI
        $citasHoy = Reservation::activas()->whereDate('fecha', $hoy)->count();
        
        $ingresosMes = Reservation::completadas()
            ->delMes($mesActual, $anioActual)
            ->sum('total');
            
        $clientesNuevos = Client::whereMonth('created_at', $mesActual)
            ->whereYear('created_at', $anioActual)
            ->count();
            
        $reservasPendientes = Reservation::where('estado', 'pendiente')->count();

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

        // 5. Datos para la Gráfica (Últimos 6 meses de ingresos)
        $labelsGrafica = [];
        $datosGrafica = [];

        for ($i = 5; $i >= 0; $i--) {
            $mes = Carbon::now()->subMonths($i);
            
            $ingresoMes = Reservation::completadas()
                ->delMes($mes->month, $mes->year)
                ->sum('total');

            $labelsGrafica[] = ucfirst($mes->translatedFormat('F')); // Ej: Julio
            $datosGrafica[] = $ingresoMes;
        }

        // 6. Retornar Vista
        return view('dashboard.index', compact(
            'citasHoy', 'ingresosMes', 'clientesNuevos', 'reservasPendientes',
            'proximasCitas', 'inventarioCritico', 'topBarberos', 'topServicios',
            'labelsGrafica', 'datosGrafica'
        ));
    }
}
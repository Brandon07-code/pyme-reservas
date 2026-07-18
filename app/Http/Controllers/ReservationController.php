<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Support\Facades\Gate; // <-- NUEVO: Importamos Gate para Laravel 11

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $estadoFilter = $request->get('estado'); 
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $reservaIdFilter = $request->get('reserva_id');

        $usuario = auth()->user();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        
        $query = Reservation::with(['client', 'employee.user']);

        // Aplicar filtros de la Guía 2
        $query->buscar($search)
              ->filtrarEstado($estadoFilter)
              ->fechas($fechaInicio, $fechaFin);

        // Lógica de acceso para el empleado
        if ($usuario->role_id == 2 && $usuario->employee) {
            $query->where('employee_id', $usuario->employee->id);
        }

        if ($reservaIdFilter) {
            $query->where('id', $reservaIdFilter);
        }

        $reservations = $query->latest('fecha')->latest('hora_inicio')->paginate(10)->appends(request()->query());

        $statQuery = Reservation::delMes($mesActual, $anioActual);
        if ($usuario->role_id == 2 && $usuario->employee) {
            $statQuery->where('employee_id', $usuario->employee->id);
        }

        $total = (clone $statQuery)->count();
        $pendientes = (clone $statQuery)->where('estado', 'pendiente')->count();
        $confirmadas = (clone $statQuery)->where('estado', 'confirmada')->count();
        $completadas = (clone $statQuery)->where('estado', 'completada')->count();
        $canceladas = (clone $statQuery)->where('estado', 'cancelada')->count();

        return view('reservations.index', compact('reservations', 'search', 'estadoFilter', 'fechaInicio', 'fechaFin', 'total', 'pendientes', 'confirmadas', 'completadas', 'canceladas'));
    }

    public function markAsCompleted(Reservation $reserva)
    {
        // 🔒 Laravel 11 Standard Policy check
        Gate::authorize('update', $reserva);

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return redirect()->back()->withErrors('Esta reserva ya está cerrada.');
        }

        $fechaFmt = Carbon::parse($reserva->fecha)->format('Y-m-d');
        $fechaHoraFin = Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $reserva->hora_fin);
        
        if (now()->lessThan($fechaHoraFin)) {
            return redirect()->back()->withErrors('No puedes completar una cita antes de que termine su horario programado.');
        }

        $reserva->update(['estado' => 'completada']);
        return redirect()->back()->with('success', '¡Cita marcada como completada exitosamente!');
    }

    public function create()
    {
        $clients = Client::where('estado', true)->get();
        $employees = Employee::with('user')->where('estado', true)->get();
        $services = Service::where('estado', true)->get();
        
        return view('reservations.create', compact('clients', 'employees', 'services'));
    }

    public function store(StoreReservationRequest $request)
    {
        $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
        $precioTotal = $serviciosSeleccionados->sum('precio');
        $duracionTotalMinutos = (int) $serviciosSeleccionados->sum('duracion_minutos');
        
        $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

        $reserva = Reservation::create([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente',
            'total' => $precioTotal
        ]);

        foreach ($serviciosSeleccionados as $servicio) {
            $reserva->services()->attach($servicio->id, [
                'precio_historico' => $servicio->precio,
                'duracion_historica' => $servicio->duracion_minutos,
            ]);
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva agendada correctamente.');
    }

    public function edit(Reservation $reserva)
    {
        // 🔒 Laravel 11 Standard Policy check
        Gate::authorize('update', $reserva);
        return view('reservations.edit', compact('reserva'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reserva)
    {
        // 🔒 Laravel 11 Standard Policy check
        Gate::authorize('update', $reserva);
        $reserva->update(['estado' => $request->estado]);
        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }

    public function destroy(Reservation $reserva)
    {
        
        Gate::authorize('delete', $reserva);
        $reserva->delete(); 
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada permanentemente del sistema.');
    }
}
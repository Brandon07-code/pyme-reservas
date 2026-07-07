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
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $reservations = Reservation::with(['client', 'employee.user'])
                            ->search($search)
                            ->latest('fecha')
                            ->latest('hora_inicio')
                            ->paginate(10);

        $total = Reservation::count();
        $pendientes = Reservation::where('estado', 'pendiente')->count();
        $completadas = Reservation::where('estado', 'completada')->count();

        return view('reservations.index', compact('reservations', 'search', 'total', 'pendientes', 'completadas'));
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
        // 1. Validar choque de horarios (Evitar error SQL)
        $existeChoque = Reservation::where('employee_id', $request->employee_id)
            ->where('fecha', $request->fecha)
            ->where('hora_inicio', $request->hora_inicio)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->exists();

        if ($existeChoque) {
            throw ValidationException::withMessages([
                'hora_inicio' => 'El barbero seleccionado ya tiene una cita confirmada en esta fecha y hora.'
            ]);
        }

        // 2. Cálculos automáticos
        $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
        $precioTotal = $serviciosSeleccionados->sum('precio');
        $duracionTotalMinutos = $serviciosSeleccionados->sum('duracion_minutos');
        $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

        // 3. Crear Reserva
        $reserva = Reservation::create([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente',
            'total' => $precioTotal
        ]);

        // 4. Llenar tabla pivote
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
        return view('reservations.edit', compact('reserva'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reserva)
    {
        $reserva->update(['estado' => $request->estado]);
        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }

    public function destroy(Reservation $reserva)
    {
        $reserva->delete(); 
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada permanentemente del sistema.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Service;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['client', 'employee.user'])->latest('fecha')->latest('hora_inicio')->paginate(10);
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $clients = Client::where('estado', true)->get();
        $employees = Employee::with('user')->where('estado', true)->get();
        $services = Service::where('estado', true)->get();
        
        return view('reservations.create', compact('clients', 'employees', 'services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id'
        ]);

        // 1. Obtener los servicios seleccionados desde la BD para saber su precio y duración ACTUAL
        $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
        
        // 2. Calcular los totales automáticamente
        $precioTotal = $serviciosSeleccionados->sum('precio');
        $duracionTotalMinutos = $serviciosSeleccionados->sum('duracion_minutos');
        
        // Calcular hora_fin usando Carbon (Librería de fechas de Laravel)
        $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

        // 3. Crear la Reserva maestra
        $reserva = Reservation::create([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente',
            'total' => $precioTotal
        ]);

        // 4. Llenar la tabla pivote con los datos históricos
        foreach ($serviciosSeleccionados as $servicio) {
            $reserva->services()->attach($servicio->id, [
                'precio_historico' => $servicio->precio,
                'duracion_historica' => $servicio->duracion_minutos,
            ]);
        }

        return redirect()->route('reservas.index')->with('success', 'Reserva agendada correctamente.');
    }

    // Nota: El edit y update los dejaremos básicos para cambiar el estado, ya que editar los servicios de una reserva ya agendada requiere lógica compleja de recálculo y control de disponibilidad.
    public function edit(Reservation $reserva)
    {
        return view('reservations.edit', compact('reserva'));
    }

    public function update(Request $request, Reservation $reserva)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada'
        ]);

        $reserva->update(['estado' => $request->estado]);
        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }

    public function destroy(Reservation $reserva)
    {
        $reserva->update(['estado' => 'cancelada']);
        return redirect()->route('reservas.index')->with('success', 'Reserva cancelada correctamente.');
    }
}
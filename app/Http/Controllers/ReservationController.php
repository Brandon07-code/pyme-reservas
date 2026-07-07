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
    // ==========================================
    // METODO PRIVADO DE SEGURIDAD (ANTI-IDOR)
    // ==========================================
    private function authorizeEmployee(Reservation $reserva)
    {
        $user = auth()->user();
        // Si es empleado (2) y la cita no es suya, se bloquea el acceso.
        if ($user->role_id == 2) {
            if (!$user->employee || $reserva->employee_id !== $user->employee->id) {
                abort(403, 'Acceso denegado. Esta reserva pertenece a la agenda de otro empleado.');
            }
        }
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $estadoFilter = $request->get('estado'); 
        $usuario = auth()->user();
        $mesActual = Carbon::now()->month;
        $anioActual = Carbon::now()->year;
        
        $query = Reservation::with(['client', 'employee.user'])->search($search);

        if ($usuario->role_id == 2 && $usuario->employee) {
            $query->where('employee_id', $usuario->employee->id);
        }

        if ($estadoFilter) {
            $query->where('estado', $estadoFilter);
        }

        $reservations = $query->latest('fecha')->latest('hora_inicio')->paginate(10);

        $statQuery = Reservation::delMes($mesActual, $anioActual);
        if ($usuario->role_id == 2 && $usuario->employee) {
            $statQuery->where('employee_id', $usuario->employee->id);
        }

        $total = (clone $statQuery)->count();
        $pendientes = (clone $statQuery)->where('estado', 'pendiente')->count();
        $confirmadas = (clone $statQuery)->where('estado', 'confirmada')->count();
        $completadas = (clone $statQuery)->where('estado', 'completada')->count();
        $canceladas = (clone $statQuery)->where('estado', 'cancelada')->count();

        return view('reservations.index', compact('reservations', 'search', 'estadoFilter', 'total', 'pendientes', 'confirmadas', 'completadas', 'canceladas'));
    }

    public function markAsCompleted(Reservation $reserva)
    {
        $this->authorizeEmployee($reserva);

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return redirect()->back()->withErrors('Esta reserva ya está cerrada.');
        }

        // Lógica de tiempo
        $fechaHoraFin = Carbon::parse($reserva->fecha . ' ' . $reserva->hora_fin);
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
        // Cálculos automáticos
        $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
        $precioTotal = $serviciosSeleccionados->sum('precio');
        
        // CASTING A INTEGER
        $duracionTotalMinutos = (int) $serviciosSeleccionados->sum('duracion_minutos');
        
        $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

        // Crear Reserva
        $reserva = Reservation::create([
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
            'fecha' => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin' => $horaFin,
            'estado' => 'pendiente',
            'total' => $precioTotal
        ]);

        // Llenar tabla pivote
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
        $this->authorizeEmployee($reserva);
        return view('reservations.edit', compact('reserva'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reserva)
    {
        $this->authorizeEmployee($reserva);
        $reserva->update(['estado' => $request->estado]);
        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }

    public function destroy(Reservation $reserva)
    {
        $this->authorizeEmployee($reserva);
        $reserva->delete(); 
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada permanentemente del sistema.');
    }
}
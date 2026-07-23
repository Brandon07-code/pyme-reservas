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
use App\Events\ReservationCreated;
use App\Events\ReservationUpdated;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', 300); // 5 minutos para DomPDF
        
        $search = $request->get('search');
        $estadoFilter = $request->get('estado'); 
        $fechaInicio = $request->get('fecha_inicio');
        $fechaFin = $request->get('fecha_fin');
        $usuario = auth()->user();

        $query = Reservation::with(['client', 'employee.user']);

        $query->buscar($search)
              ->filtrarEstado($estadoFilter)
              ->fechas($fechaInicio, $fechaFin);

        if ($usuario->role_id == 2 && $usuario->employee) {
            $query->where('employee_id', $usuario->employee->id);
        }

        $reservations = $query->latest('fecha')->latest('hora_inicio')->get();

        $pdf = Pdf::loadView('pdf.reservas', compact('reservations'));
        
        return $pdf->download('reporte-citas-jym-' . date('Y-m-d') . '.pdf');
    }

    public function markAsConfirmed(Reservation $reserva)
    {
        Gate::authorize('update', $reserva);

        if ($reserva->estado !== 'pendiente') {
            return redirect()->back()->withErrors('Solo las citas pendientes pueden ser confirmadas rápidamente.');
        }

        $reserva->update(['estado' => 'confirmada']);
        event(new ReservationUpdated($reserva));
        
        return redirect()->back()->with('success', '¡Cita confirmada exitosamente!');
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
        event(new ReservationUpdated($reserva));
        
        return redirect()->back()->with('success', '¡Cita marcada como completada exitosamente!');
    }

    public function create()
    {
        $clients = Client::where('estado', true)->get();
        
        $employeesQuery = Employee::with('user')->where('estado', true);
        if (auth()->check() && auth()->user()->employee) {
            $employeesQuery->where('id', '!=', auth()->user()->employee->id);
        }
        $employees = $employeesQuery->get();
        
        $services = Service::where('estado', true)->get();
        
        return view('reservations.create', compact('clients', 'employees', 'services'));
    }

    public function store(StoreReservationRequest $request)
    {
        if (auth()->check() && auth()->user()->employee && auth()->user()->employee->id == $request->employee_id) {
            return redirect()->back()->withErrors('No puedes agendar una cita para ser atendido por ti mismo.')->withInput();
        }

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

        event(new ReservationCreated($reserva));

        return redirect()->route('reservas.index')->with('success', 'Reserva agendada correctamente.');
    }

    public function edit(Reservation $reserva)
    {
        // 🔒 Laravel 11 Standard Policy check
        Gate::authorize('update', $reserva);

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return redirect()->route('reservas.index')->withErrors('Esta reserva ya está cerrada y no se puede editar.');
        }

        return view('reservations.edit', compact('reserva'));
    }

    public function update(UpdateReservationRequest $request, Reservation $reserva)
    {
        // 🔒 Laravel 11 Standard Policy check
        Gate::authorize('update', $reserva);

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return redirect()->route('reservas.index')->withErrors('Esta reserva ya está cerrada y no se puede modificar.');
        }

        if ($reserva->estado === 'confirmada' && $request->estado === 'pendiente') {
            return redirect()->back()->withErrors('Una reserva confirmada no puede volver a estar pendiente.');
        }

        $reserva->update(['estado' => $request->estado]);
        event(new ReservationUpdated($reserva));
        
        return redirect()->route('reservas.index')->with('success', 'Estado de la reserva actualizado.');
    }

    public function destroy(Reservation $reserva)
    {
        
        Gate::authorize('delete', $reserva);
        $reserva->delete(); 
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada permanentemente del sistema.');
    }
}
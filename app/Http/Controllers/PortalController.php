<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Product;
use App\Models\ServiceCategory;
use App\Models\Employee;
use App\Models\Reservation;
use App\Models\Schedule;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\User;
use App\Notifications\NuevaReservaNotification;
use Illuminate\Support\Facades\Notification;

class PortalController extends Controller
{
    public function index()
    {
        $categoriasServicios = ServiceCategory::with(['services' => function($query) {
            $query->where('estado', 1);
        }])->where('estado', 1)->get();

        $productos = Product::where('estado', 1)->latest()->get();

        return view('portal.index', compact('categoriasServicios', 'productos'));
    }

    public function agendar()
    {
        $services = Service::where('estado', 1)->get();
        $employees = Employee::with('user')->where('estado', 1)->get();
        
        return view('portal.agendar', compact('services', 'employees'));
    }

    public function getDisponibilidad(Request $request)
    {
        $fecha = Carbon::parse($request->fecha);
        $hoy = Carbon::today();
        
        if ($fecha->isBefore($hoy)) {
            return response()->json([]);
        }

        $diaSemana = $fecha->dayOfWeekIso;
        $barberoId = $request->employee_id;
        $duracionTotal = (int) $request->duracion_minutos;

        $turno = Schedule::where('employee_id', $barberoId)
                         ->where('dia_semana', $diaSemana)
                         ->where('disponible', 1)
                         ->first();

        if (!$turno) {
            return response()->json([]); 
        }

        $citasOcupadas = Reservation::where('employee_id', $barberoId)
            ->whereDate('fecha', $fecha->format('Y-m-d'))
            ->whereIn('estado', ['pendiente', 'confirmada', 'completada'])
            ->get();

        $horasLibres = [];
        $horaActual = Carbon::parse($fecha->format('Y-m-d') . ' ' . $turno->hora_inicio);
        $horaSalida = Carbon::parse($fecha->format('Y-m-d') . ' ' . $turno->hora_fin);
        $ahoraMismo = Carbon::now();

        while ($horaActual->copy()->addMinutes($duracionTotal)->lte($horaSalida)) {
            if ($fecha->isToday() && $horaActual->isBefore($ahoraMismo)) {
                $horaActual->addMinutes(15);
                continue;
            }

            $inicioPropuesto = $horaActual->copy();
            $finPropuesto = $horaActual->copy()->addMinutes($duracionTotal);
            $choca = false;

            foreach ($citasOcupadas as $cita) {
                $inicioCita = Carbon::parse($fecha->format('Y-m-d') . ' ' . $cita->hora_inicio);
                $finCita = Carbon::parse($fecha->format('Y-m-d') . ' ' . $cita->hora_fin);

                if ($inicioPropuesto->lessThan($finCita) && $finPropuesto->greaterThan($inicioCita)) {
                    $choca = true;
                    break;
                }
            }

            if (!$choca) {
                $horasLibres[] = $horaActual->format('H:i');
            }

            $horaActual->addMinutes(15);
        }

        return response()->json($horasLibres);
    }

    public function storeReserva(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id'
        ]);

        $clienteId = auth()->user()->client->id; 
        
        $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
        $precioTotal = $serviciosSeleccionados->sum('precio');
        $duracionTotalMinutos = (int) $serviciosSeleccionados->sum('duracion_minutos');
        $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

        $existeChoque = Reservation::where('employee_id', $request->employee_id)
            ->where('fecha', $request->fecha)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->where(function ($query) use ($request, $horaFin) {
                $query->where('hora_inicio', '<', $horaFin)
                      ->where('hora_fin', '>', $request->hora_inicio);
            })->exists();

        if ($existeChoque) {
            return redirect()->back()->withErrors('Lo sentimos, alguien acaba de reservar esa misma hora. Intenta con otra.');
        }

        $reserva = Reservation::create([
            'client_id' => $clienteId,
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

        $admins = User::where('role_id', 1)->get();
        Notification::send($admins, new NuevaReservaNotification($reserva));
        
        $barberoUser = User::find($reserva->employee->user_id);
        if($barberoUser) {
            $barberoUser->notify(new NuevaReservaNotification($reserva));
        }

        return redirect()->route('portal.index')->with('success', '¡Tu cita ha sido agendada con éxito!');
    }

    public function misCitas()
    {
        $clienteId = auth()->user()->client->id;
        
        $reservas = Reservation::with(['employee.user', 'services'])
            ->where('client_id', $clienteId)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'desc')
            ->paginate(10);

        return view('portal.citas', compact('reservas'));
    }

    public function cancelarCita(Reservation $reserva)
    {
        if ($reserva->client_id !== auth()->user()->client->id) {
            abort(403, 'No tienes permiso para cancelar esta cita.');
        }

        $fechaFmt = Carbon::parse($reserva->fecha)->format('Y-m-d');
        $fechaHoraInicio = Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $reserva->hora_inicio);
        
        if (now()->greaterThanOrEqualTo($fechaHoraInicio)) {
            return redirect()->back()->withErrors('No puedes cancelar una cita que ya pasó o está en curso.');
        }

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return redirect()->back()->withErrors('Esta cita ya se encuentra cerrada en el sistema.');
        }

        $reserva->update(['estado' => 'cancelada']);

        return redirect()->back()->with('success', 'Tu cita ha sido cancelada correctamente. ¡Te esperamos pronto!');
    }

    // ==========================================
    // ==========================================
    public function misPedidos()
    {
        $clienteId = auth()->user()->client->id;
        
        // Cargar los pedidos con sus productos y paginarlos
        $pedidos = Order::with('products')
            ->where('client_id', $clienteId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('portal.pedidos', compact('pedidos'));
    }

public function cancelarPedido(Order $order)
    {
        if ($order->client_id !== auth()->user()->client->id) {
            abort(403, 'No tienes permiso para alterar este pedido.');
        }

        if (!in_array($order->estado, ['pendiente', 'pendiente_recogida'])) {
            return redirect()->back()->withErrors('Este pedido ya fue entregado o cancelado previamente.');
        }

        foreach ($order->products as $producto) {
            $producto->increment('stock_actual', $producto->pivot->cantidad);
        }

        $order->update(['estado' => 'cancelado']);

        User::where('role_id', 1)->get()->each(function ($admin) use ($order) {
            $admin->unreadNotifications
                ->where('data.tipo', 'pedido')
                ->where('data.pedido_id', $order->id)
                ->each->markAsRead();
        });

        return redirect()->back()->with('success', 'Pedido cancelado. El inventario ha sido devuelto a la tienda.');
    }
}
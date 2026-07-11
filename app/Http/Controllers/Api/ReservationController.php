<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Service;
use App\Http\Requests\StoreReservationRequest; // <- Tu Firewall
use App\Http\Resources\ReservationResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        
        $query = Reservation::with(['client.user', 'employee.user', 'services']);

        // ANTI-IDOR: Filtramos lo que ve cada quien según su rol
        if ($user->role_id == 2) { // Barbero
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->role_id == 3) { // Cliente
            $query->where('client_id', $user->client->id);
        } // Si es Admin (1), pasa de largo y ve todo.

        return ReservationResource::collection($query->orderBy('fecha', 'desc')->get());
    }

    public function store(StoreReservationRequest $request)
    {
        // Si llega aquí, el Motor Tetris ya aprobó que NO HAY CRUCES.
        $validated = $request->validated();

        $reserva = DB::transaction(function () use ($validated, $request) {
            
            // 1. Crear Reserva Base
            $reserva = Reservation::create([
                'client_id'   => $validated['client_id'] ?? auth('api')->user()->client->id,
                'employee_id' => $validated['employee_id'],
                'fecha'       => $validated['fecha'],
                'hora_inicio' => $validated['hora_inicio'],
                'hora_fin'    => $validated['hora_fin'],
                'estado'      => 'pendiente',
                'total'       => 0 
            ]);

            // 2. Adjuntar servicios y congelar el PRECIO HISTÓRICO
            $total = 0;
            $serviciosPivot = [];
            
            foreach ($request->service_ids as $service_id) {
                $servicio = Service::find($service_id);
                $serviciosPivot[$service_id] = [
                    'precio_historico'   => $servicio->precio,
                    'duracion_historica' => $servicio->duracion_minutos,
                    'observaciones'      => $request->observaciones ?? null
                ];
                $total += $servicio->precio;
            }

            $reserva->services()->sync($serviciosPivot);
            
            // 3. Actualizar total financiero
            $reserva->update(['total' => $total]);

            return $reserva;
        });

        return response()->json(new ReservationResource($reserva->load(['client', 'employee.user', 'services'])), 201);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);
        $user = auth('api')->user();

        // Candado Histórico: Bloquear si ya ocurrió o se canceló
        if (in_array($reservation->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return response()->json(['error' => 'Registro bloqueado por historial. No se puede eliminar.'], 403);
        }

        // Anti-IDOR en eliminación: Solo admin o el dueño pueden borrar
        if ($user->role_id == 3 && $reservation->client_id !== $user->client->id) {
            return response()->json(['error' => 'No autorizado para cancelar esta cita.'], 403);
        }

        // Eliminación física (Cascada limpia el pivote automáticamente)
        $reservation->delete();

        return response()->json(null, 204);
    }
}
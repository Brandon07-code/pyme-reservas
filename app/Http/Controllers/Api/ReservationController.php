<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Service;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Resources\ReservationResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();
        $query = Reservation::with(['client.user', 'employee.user', 'services']);

        if ($user->role_id == 2) {
            $query->where('employee_id', $user->employee->id);
        } elseif ($user->role_id == 3) {
            $query->where('client_id', $user->client->id);
        }

        return ReservationResource::collection($query->get());
    }

    public function store(StoreReservationRequest $request)
    {
        $validated = $request->validated();
        
        $reserva = DB::transaction(function () use ($request, $validated) {
            
            // 1. Calcular totales y tiempos según tu lógica de la Web
            $serviciosSeleccionados = Service::whereIn('id', $request->servicios)->get();
            $precioTotal = $serviciosSeleccionados->sum('precio');
            $duracionTotalMinutos = (int) $serviciosSeleccionados->sum('duracion_minutos');
            $horaFin = Carbon::parse($request->hora_inicio)->addMinutes($duracionTotalMinutos)->format('H:i');

            // 2. Crear la reserva
            $reserva = Reservation::create([
                'client_id'   => $request->client_id ?? auth('api')->user()->client->id,
                'employee_id' => $request->employee_id,
                'fecha'       => $request->fecha,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin'    => $horaFin,
                'estado'      => 'pendiente',
                'total'       => $precioTotal
            ]);

            // 3. Sincronizar el pivote (Integridad Histórica)
            $serviciosPivot = [];
            foreach ($serviciosSeleccionados as $servicio) {
                $serviciosPivot[$servicio->id] = [
                    'precio_historico'   => $servicio->precio,
                    'duracion_historica' => $servicio->duracion_minutos,
                    'observaciones'      => $request->observaciones ?? null
                ];
            }
            $reserva->services()->sync($serviciosPivot);

            return $reserva; 
        });

        return response()->json(new ReservationResource($reserva->load('services')), 201);
    }

    public function destroy($id)
    {
        $reservation = Reservation::findOrFail($id);

        if (in_array($reservation->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return response()->json(['error' => 'Registro bloqueado por historial.'], 403);
        }

        $reservation->delete();
        return response()->json(null, 204);
    }
}
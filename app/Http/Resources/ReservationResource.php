<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReservationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'fecha'       => $this->fecha,
            'horario'     => $this->hora_inicio . ' - ' . $this->hora_fin,
            'estado'      => $this->estado,
            'total'       => $this->total,
            
            // Relaciones protegidas (Cargan solo si se pidieron en el controlador)
            'cliente' => $this->whenLoaded('client', function () {
                return $this->client->primer_nombre . ' ' . $this->client->primer_apellido;
            }),
            
            'barbero' => $this->whenLoaded('employee', function () {
                return $this->employee->user->primer_nombre . ' ' . $this->employee->user->primer_apellido;
            }),
            
            // Servicios y datos del pivote histórico
            'servicios' => $this->whenLoaded('services', function () {
                return $this->services->map(function ($servicio) {
                    return [
                        'nombre'             => $servicio->nombre,
                        'precio_cobrado'     => $servicio->pivot->precio_historico,
                        'duracion_agendada'  => $servicio->pivot->duracion_historica,
                    ];
                });
            }),
        ];
    }
}
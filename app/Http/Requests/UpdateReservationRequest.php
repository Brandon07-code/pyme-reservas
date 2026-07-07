<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reserva = $this->route('reserva');

        // Candado del pasado: Si ya se cerró, no se toca.
        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return ['estado' => 'prohibited'];
        }

        // Lógica de línea de tiempo
        $fechaHoraFin = Carbon::parse($reserva->fecha . ' ' . $reserva->hora_fin);
        
        if (now()->lessThan($fechaHoraFin)) {
            // Futuro: No puede estar completada ni reportar inasistencia
            return ['estado' => 'required|in:pendiente,confirmada,cancelada'];
        } else {
            // Pasado: Si ya terminó, debe cerrarse definitivamente
            return ['estado' => 'required|in:completada,cancelada,no_asistio'];
        }
    }

    public function messages(): array
    {
        return [
            'estado.prohibited' => 'Esta reserva ya está en un estado final y no puede ser modificada.',
            'estado.in' => 'El estado seleccionado no es lógico para la fecha y hora de la cita.'
        ];
    }
}
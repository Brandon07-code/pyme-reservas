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

        if (in_array($reserva->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return ['estado' => 'prohibited'];
        }

        $fechaFmt = Carbon::parse($reserva->fecha)->format('Y-m-d');
        $fechaHoraFin = Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $reserva->hora_fin);
        
        if (now()->lessThan($fechaHoraFin)) {
            return ['estado' => 'required|in:pendiente,confirmada,cancelada'];
        } else {
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
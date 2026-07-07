<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $reservaOriginal = $this->route('reserva');

        // REGLA DE NEGOCIO: Si la reserva ya fue cerrada, nadie puede alterar su estado.
        if (in_array($reservaOriginal->estado, ['completada', 'cancelada', 'no_asistio'])) {
            return [
                'estado' => 'prohibited' // Falla la validación automáticamente
            ];
        }

        return [
            'estado' => 'required|in:pendiente,confirmada,completada,cancelada,no_asistio'
        ];
    }

    public function messages(): array
    {
        return [
            'estado.prohibited' => 'Esta reserva ya está en un estado final (Completada/Cancelada/No Asistió) y no puede ser modificada.'
        ];
    }
}
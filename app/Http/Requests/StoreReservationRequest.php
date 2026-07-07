<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; 
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'fecha' => 'required|date|after_or_equal:today', // REGLA: No crear citas en el pasado
            'hora_inicio' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id'
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Debes seleccionar un cliente.',
            'employee_id.required' => 'Debes seleccionar un barbero.',
            'fecha.after_or_equal' => 'La fecha de la cita no puede ser en el pasado.',
            'servicios.required' => 'Debes seleccionar al menos un servicio.',
        ];
    }
}
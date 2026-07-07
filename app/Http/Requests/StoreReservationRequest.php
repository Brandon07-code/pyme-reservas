<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // True permite que cualquier usuario logueado use este formulario
        return true; 
    }

    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'employee_id' => 'required|exists:employees,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id'
        ];
    }

    // Mensajes personalizados en español
    public function messages(): array
    {
        return [
            'client_id.required' => 'Debes seleccionar un cliente.',
            'employee_id.required' => 'Debes seleccionar un barbero.',
            'servicios.required' => 'Debes seleccionar al menos un servicio.',
        ];
    }
}
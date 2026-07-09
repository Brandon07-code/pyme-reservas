<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->role_id == 1; // Solo admin modifica horarios
    }

    public function rules(): array
    {
        return [
            'horarios' => 'required|array|size:7',
            'horarios.*.hora_inicio' => 'required|date_format:H:i',
            'horarios.*.hora_fin' => 'required|date_format:H:i|after:horarios.*.hora_inicio',
        ];
    }

    public function messages(): array
    {
        return [
            'horarios.*.hora_fin.after' => 'La hora de salida debe ser mayor a la hora de entrada en todos los días habilitados.',
        ];
    }
}
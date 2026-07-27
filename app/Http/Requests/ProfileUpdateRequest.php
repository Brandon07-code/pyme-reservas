<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'primer_nombre' => ['required', 'string', 'max:100'],
            'segundo_nombre' => ['nullable', 'string', 'max:100'],
            'primer_apellido' => ['required', 'string', 'max:100'],
            'segundo_apellido' => ['nullable', 'string', 'max:100'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:150',
                Rule::unique(User::class)->ignore($this->user()->id),
                function ($attribute, $value, $fail) {
                    $roleId = (int) $this->user()->role_id;
                    $isCorporate = str_ends_with(strtolower($value), '@pymereservas.com');
                    
                    if (in_array($roleId, [1, 2]) && !$isCorporate) {
                        $fail('Tu cuenta de administrador/empleado debe usar el correo corporativo exclusivo (@pymereservas.com).');
                    }
                    if ($roleId === 3 && $isCorporate) {
                        $fail('Tu cuenta de cliente no puede usar el dominio reservado de la empresa.');
                    }
                }
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];
    }
}
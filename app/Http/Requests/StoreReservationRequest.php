<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Models\Schedule;
use App\Models\Reservation;
use App\Models\Service;

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
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => ['required', 'date_format:H:i', 'regex:/^(0[0-9]|1[0-9]|2[0-3]):(00|30)$/'],
            'servicios' => 'required|array|min:1',
            'servicios.*' => 'exists:services,id'
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'Debes seleccionar un cliente.',
            'employee_id.required' => 'Debes seleccionar un barbero.',
            'fecha.after_or_equal' => 'La fecha de la cita no puede estar en el pasado.',
            'hora_inicio.regex' => 'Las citas solo pueden iniciar en intervalos de 30 minutos (Ej: 08:00, 08:30).',
            'servicios.required' => 'Debes seleccionar al menos un servicio.',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if ($validator->errors()->isNotEmpty()) return;

                $fecha = Carbon::parse($this->fecha)->format('Y-m-d');
                $horaSolicitada = Carbon::parse($this->hora_inicio)->format('H:i:s');
                $fechaHoraCita = Carbon::parse($fecha . ' ' . $horaSolicitada);

                // CAPA 1: CONCIENCIA TEMPORAL
                if ($fechaHoraCita->isPast()) {
                    $validator->errors()->add('hora_inicio', 'No puedes agendar una cita en una hora que ya pasó.');
                    return;
                }

                // CAPA 2: HORARIOS LABORALES
                $diaSemana = $fechaHoraCita->dayOfWeekIso;
                $turno = Schedule::where('employee_id', $this->employee_id)
                                 ->where('dia_semana', $diaSemana)
                                 ->first();

                if (!$turno || !$turno->disponible) {
                    $validator->errors()->add('fecha', 'El barbero seleccionado no labora este día.');
                    return;
                }

                if ($horaSolicitada < $turno->hora_inicio || $horaSolicitada >= $turno->hora_fin) {
                    $horaIFormat = Carbon::parse($turno->hora_inicio)->format('h:i A');
                    $horaFFormat = Carbon::parse($turno->hora_fin)->format('h:i A');
                    $validator->errors()->add('hora_inicio', "Fuera del horario. El turno de este barbero es de {$horaIFormat} a {$horaFFormat}.");
                    return;
                }

                // CAPA 3: EL TETRIS (Traslapes y Colisiones)
                // CASTING A INTEGER (La solución al error 500)
                $duracionTotal = (int) Service::whereIn('id', $this->servicios)->sum('duracion_minutos');
                $horaFinSolicitada = $fechaHoraCita->copy()->addMinutes($duracionTotal)->format('H:i:s');

                $choque = Reservation::where('employee_id', $this->employee_id)
                    ->where('fecha', $fecha)
                    ->whereIn('estado', ['pendiente', 'confirmada', 'completada'])
                    ->where(function ($query) use ($horaSolicitada, $horaFinSolicitada) {
                        $query->where('hora_inicio', '<', $horaFinSolicitada)
                              ->where('hora_fin', '>', $horaSolicitada);
                    })
                    ->exists();

                $choqueExacto = Reservation::where('employee_id', $this->employee_id)
                    ->where('fecha', $fecha)
                    ->where('hora_inicio', $horaSolicitada)
                    ->exists();

                if ($choque || $choqueExacto) {
                    $validator->errors()->add('hora_inicio', 'El barbero ya tiene una cita bloqueando este rango de horario.');
                }
            }
        ];
    }
}
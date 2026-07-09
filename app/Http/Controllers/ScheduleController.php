<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Schedule;
use App\Http\Requests\UpdateScheduleRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    public function edit(Employee $empleado)
    {
        $diasSemana = [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
            4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo'
        ];

        // Mapear los horarios actuales del empleado usando el día de la semana como llave
        $horariosActuales = $empleado->schedules->keyBy('dia_semana');

        return view('schedules.edit', compact('empleado', 'diasSemana', 'horariosActuales'));
    }

    public function update(UpdateScheduleRequest $request, Employee $empleado)
    {
        foreach ($request->horarios as $dia => $datos) {
            
            // Si el checkbox de 'disponible' no viene en el request, asumimos false
            $disponible = isset($datos['disponible']) ? true : false;

            // Aseguramos formato correcto (H:i:s)
            $horaInicio = Carbon::parse($datos['hora_inicio'])->format('H:i:s');
            $horaFin = Carbon::parse($datos['hora_fin'])->format('H:i:s');

            Schedule::updateOrCreate(
                ['employee_id' => $empleado->id, 'dia_semana' => $dia],
                [
                    'disponible' => $disponible,
                    'hora_inicio' => $horaInicio,
                    'hora_fin' => $horaFin
                ]
            );
        }

        return redirect()->route('empleados.index')->with('success', 'Turnos laborales de ' . $empleado->user->primer_nombre . ' actualizados correctamente.');
    }
}
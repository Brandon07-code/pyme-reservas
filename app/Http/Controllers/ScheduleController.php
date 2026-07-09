<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Schedule;
use App\Models\Reservation;
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

        $horariosActuales = $empleado->schedules->keyBy('dia_semana');

        return view('schedules.edit', compact('empleado', 'diasSemana', 'horariosActuales'));
    }

    public function update(UpdateScheduleRequest $request, Employee $empleado)
    {
        $errores = [];
        $hoy = Carbon::today()->format('Y-m-d');
        $nombresDias = [1=>'Lunes', 2=>'Martes', 3=>'Miércoles', 4=>'Jueves', 5=>'Viernes', 6=>'Sábado', 7=>'Domingo'];

        foreach ($request->horarios as $dia => $datos) {
            $disponible = isset($datos['disponible']) ? true : false;
            $horaInicio = Carbon::parse($datos['hora_inicio'])->format('H:i:s');
            $horaFin = Carbon::parse($datos['hora_fin'])->format('H:i:s');

            $mysqlWeekday = $dia - 1; 

            $citasFuturas = Reservation::where('employee_id', $empleado->id)
                ->where('fecha', '>=', $hoy)
                ->whereIn('estado', ['pendiente', 'confirmada'])
                ->whereRaw('WEEKDAY(fecha) = ?', [$mysqlWeekday])
                ->get();

            if ($citasFuturas->isNotEmpty()) {
                if (!$disponible) {
                    $errores[] = "No puedes apagar el {$nombresDias[$dia]} porque el empleado ya tiene citas activas programadas en fechas futuras para ese día, por favor cancela o reprograma esas citas primero.";
                } else {
                    foreach ($citasFuturas as $cita) {
                        if ($cita->hora_inicio < $horaInicio || $cita->hora_fin > $horaFin) {
                            $horaCita = Carbon::parse($cita->hora_inicio)->format('h:i A');
                            $errores[] = "No puedes reducir el horario del {$nombresDias[$dia]} porque hay citas activas fuera del nuevo rango (Ej: cita a las {$horaCita}).";
                            break;
                        }
                    }
                }
            }
        }

        if (!empty($errores)) {
            return redirect()->back()->withErrors($errores);
        }

        foreach ($request->horarios as $dia => $datos) {
            $disponible = isset($datos['disponible']) ? true : false;
            $horaInicio = Carbon::parse($datos['hora_inicio'])->format('H:i:s');
            $horaFin = Carbon::parse($datos['hora_fin'])->format('H:i:s');

            Schedule::updateOrCreate(
                ['employee_id' => $empleado->id, 'dia_semana' => $dia],
                ['disponible' => $disponible, 'hora_inicio' => $horaInicio, 'hora_fin' => $horaFin]
            );
        }

        return redirect()->route('empleados.index')->with('success', 'Turnos laborales de ' . $empleado->user->primer_nombre . ' actualizados correctamente.');
    }
}
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class MarcarCitasVencidas extends Command
{
    /**
     * Nombre del comando para llamarlo desde consola o scheduler.
     */
    protected $signature = 'reservas:marcar-vencidas';

    /**
     * Descripción del comando.
     */
    protected $description = 'Procesa citas pasadas: pendientes a "No Asistió", confirmadas a "Completada".';

    /**
     * Ejecutar el comando.
     */
    public function handle(): void
    {
        $ahora = Carbon::now();

        // Buscamos todas las citas pendientes o confirmadas de fechas <= hoy
        $citasCandidatas = Reservation::whereIn('estado', ['pendiente', 'confirmada'])
            ->where('fecha', '<=', $ahora->toDateString())
            ->get();

        // Filtramos con Carbon para ser 100% agnósticos a la base de datos (evita errores en PostgreSQL)
        $citasVencidas = $citasCandidatas->filter(function ($cita) use ($ahora) {
            $fechaCita = Carbon::parse($cita->fecha)->toDateString();
            $hoy = $ahora->toDateString();
            
            // Si es de un día anterior, ya venció
            if ($fechaCita < $hoy) return true;
            
            // Si es de hoy, verificamos si la hora de fin ya pasó
            return $fechaCita === $hoy && $cita->hora_fin < $ahora->toTimeString();
        });

        $total = $citasVencidas->count();

        if ($total === 0) {
            $this->info('No hay citas vencidas para procesar.');
            return;
        }

        $noAsistioCount = 0;
        $completadasCount = 0;

        foreach ($citasVencidas as $cita) {
            if ($cita->estado === 'pendiente') {
                $cita->update(['estado' => 'no_asistio']);
                $noAsistioCount++;
            } elseif ($cita->estado === 'confirmada') {
                $cita->update(['estado' => 'completada']);
                $completadasCount++;
            }
        }

        $this->info("✅ Proceso terminado: {$noAsistioCount} a 'No Asistió', {$completadasCount} a 'Completada'.");
    }
}

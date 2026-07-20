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

        // Busca citas pendientes o confirmadas cuya hora de fin ya pasó
        $citasVencidas = Reservation::whereIn('estado', ['pendiente', 'confirmada'])
            ->whereRaw("CONCAT(fecha, ' ', hora_fin) < ?", [$ahora->format('Y-m-d H:i:s')])
            ->get();

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

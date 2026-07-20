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
    protected $description = 'Marca automáticamente como "No Asistió" las citas pendientes o confirmadas cuya fecha y hora ya pasaron.';

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

        foreach ($citasVencidas as $cita) {
            $cita->update(['estado' => 'no_asistio']);
        }

        $this->info("✅ {$total} cita(s) marcadas como 'No Asistió' correctamente.");
    }
}

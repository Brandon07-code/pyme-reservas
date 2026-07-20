<?php

namespace App\Listeners;

use App\Events\ReservationUpdated;
use App\Mail\ReservationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationUpdatedNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ReservationUpdated $event): void
    {
        $reserva = $event->reserva;
        
        try {
            // Enviar correo al cliente de la reserva informando la actualización
            if ($reserva->client && $reserva->client->user && $reserva->client->user->email) {
                Mail::to($reserva->client->user->email)->send(new ReservationNotification($reserva, 'actualizada'));
            }
        } catch (\Throwable $e) {
            // Registrar el error pero no detener la ejecución (evita el Error 500)
            \Illuminate\Support\Facades\Log::error('Error enviando notificación de reserva al cliente: ' . $e->getMessage());
        }
    }
}

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

            // Enviar correo al barbero si la cita acaba de ser "confirmada"
            if ($reserva->estado === 'confirmada') {
                if ($reserva->employee && $reserva->employee->user && $reserva->employee->user->email) {
                    Mail::to($reserva->employee->user->email)->send(new \App\Mail\BarberReservationNotification($reserva));
                }
            }
        } catch (\Exception $e) {
            // Re-lanzamos el error temporalmente para ver en pantalla por qué falla el correo del barbero
            throw $e;
        }
    }
}

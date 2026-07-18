<?php

namespace App\Listeners;

use App\Events\ReservationCreated;
use App\Mail\ReservationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendReservationCreatedNotification
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
    public function handle(ReservationCreated $event): void
    {
        $reserva = $event->reserva;
        
        // Enviar correo al cliente de la reserva
        if ($reserva->client && $reserva->client->user && $reserva->client->user->email) {
            Mail::to($reserva->client->user->email)->send(new ReservationNotification($reserva, 'creada'));
        }
    }
}

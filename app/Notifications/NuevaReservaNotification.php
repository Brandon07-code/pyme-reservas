<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class NuevaReservaNotification extends Notification
{
    use Queueable;

    protected $reserva;

    public function __construct(Reservation $reserva)
    {
        $this->reserva = $reserva;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Solo guardamos en Base de Datos (Campanita)
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'reserva',
            'reserva_id' => $this->reserva->id,
            'mensaje' => 'Nueva cita de ' . $this->reserva->client->primer_nombre,
            'fecha' => \Carbon\Carbon::parse($this->reserva->fecha)->format('d/m/Y'),
            'hora' => \Carbon\Carbon::parse($this->reserva->hora_inicio)->format('h:i A'),
        ];
    }
}
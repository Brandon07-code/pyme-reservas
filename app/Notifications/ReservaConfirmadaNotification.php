<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Reservation;

class ReservaConfirmadaNotification extends Notification
{
    use Queueable;

    protected $reserva;

    public function __construct(Reservation $reserva)
    {
        $this->reserva = $reserva;
    }

    public function via($notifiable): array
    {
        return ['database']; // Solo campanita, sin correo
    }

    public function toArray($notifiable): array
    {
        return [
            'tipo'       => 'reserva_cliente',
            'reserva_id' => $this->reserva->id,
            'mensaje'    => '✅ Tu cita ha sido CONFIRMADA para el ' .
                            \Carbon\Carbon::parse($this->reserva->fecha)->format('d/m/Y') .
                            ' a las ' .
                            \Carbon\Carbon::parse($this->reserva->hora_inicio)->format('h:i A') . '.',
            'fecha'      => \Carbon\Carbon::parse($this->reserva->fecha)->format('d/m/Y'),
            'hora'       => \Carbon\Carbon::parse($this->reserva->hora_inicio)->format('h:i A'),
        ];
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class PedidoAprobadoNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'tipo' => 'pedido_cliente',
            'pedido_id' => $this->order->id,
            'mensaje' => 'Tu pedido ha sido APROBADO. Tienes 24 horas para recogerlo.',
            'total' => $this->order->total,
        ];
    }
}

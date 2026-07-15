<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NuevoPedidoNotification extends Notification
{
    use Queueable;

    protected $pedido;

    public function __construct(Order $pedido)
    {
        $this->pedido = $pedido;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'pedido',
            'pedido_id' => $this->pedido->id,
            'mensaje' => 'Nuevo pedido Pick-up de ' . $this->pedido->client->primer_nombre,
            'total' => $this->pedido->total,
        ];
    }
}
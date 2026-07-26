@forelse($notificaciones as $notificacion)
    @php
        if ($notificacion->data['tipo'] == 'reserva') {
            $urlDestino = route('reservas.index', ['reserva_id' => $notificacion->data['reserva_id'] ?? '']);
        } elseif ($notificacion->data['tipo'] == 'reserva_cliente') {
            $urlDestino = route('portal.citas');
        } elseif ($notificacion->data['tipo'] == 'pedido_cliente') {
            $urlDestino = route('portal.pedidos');
        } else {
            $urlDestino = route('orders.index', ['pedido_id' => $notificacion->data['pedido_id'] ?? '']);
        }
    @endphp
    <a href="{{ $urlDestino }}" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition">
        <p class="text-sm font-bold text-gray-800">{{ $notificacion->data['mensaje'] }}</p>
        <p class="text-xs text-gray-500 mt-1">
            @if(in_array($notificacion->data['tipo'], ['reserva', 'reserva_cliente']))
                📅 {{ $notificacion->data['fecha'] }} a las {{ $notificacion->data['hora'] }}
            @elseif(isset($notificacion->data['total']))
                💵 Total: ${{ number_format($notificacion->data['total'], 0, ',', '.') }}
            @endif
        </p>
    </a>
@empty
    <p class="text-xs text-center text-gray-500 py-4 italic">No tienes notificaciones nuevas.</p>
@endforelse


@forelse($notificaciones as $notificacion)
    @php
        $urlDestino = $notificacion->data['tipo'] == 'reserva' 
            ? route('reservas.index', ['reserva_id' => $notificacion->data['reserva_id'] ?? ''])
            : route('orders.index', ['pedido_id' => $notificacion->data['pedido_id'] ?? '']);
    @endphp
    <a href="{{ $urlDestino }}" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition">
        <p class="text-sm font-bold text-gray-800">{{ $notificacion->data['mensaje'] }}</p>
        <p class="text-xs text-gray-500 mt-1">
            @if($notificacion->data['tipo'] == 'reserva')
                📅 {{ $notificacion->data['fecha'] }} a las {{ $notificacion->data['hora'] }}
            @else
                💵 Total: ${{ number_format($notificacion->data['total'], 0, ',', '.') }}
            @endif
        </p>
    </a>
@empty
    <p class="text-xs text-center text-gray-500 py-4 italic">No tienes notificaciones nuevas.</p>
@endforelse

@extends('layouts.client')

@section('title', 'Mis Pedidos')

@section('content')
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-black p-6 rounded-t-xl text-center border-b-4 border-[#D4AF37]">
            <h1 class="text-3xl font-extrabold text-white uppercase tracking-widest">Mis Pedidos</h1>
            <p class="text-[#D4AF37] font-semibold text-sm mt-1">Historial de tus compras en tienda</p>
        </div>

        <div class="bg-white p-6 shadow-xl rounded-b-xl border border-gray-100">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">{{ $errors->first() }}</p>
                </div>
            @endif

            @if($pedidos->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4 text-lg">Aún no has realizado pedidos de perfumería.</p>
                    <a href="{{ route('portal.index') }}#seccion-perfumeria" class="bg-[#D4AF37] hover:bg-yellow-500 text-black font-extrabold py-3 px-8 rounded-full shadow-lg transition uppercase tracking-wide">
                        ✨ Ver Catálogo
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($pedidos as $pedido)
                        <div class="border {{ $pedido->estado == 'pendiente_recogida' ? 'border-[#D4AF37]' : 'border-gray-200' }} rounded-lg overflow-hidden flex flex-col md:flex-row shadow-sm hover:shadow-md transition">
                            
                            {{-- BLOQUE FECHA Y ESTADO --}}
                            <div class="bg-gray-50 p-6 md:w-1/3 flex flex-col justify-center items-center border-b md:border-b-0 md:border-r border-gray-200">
                                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Fecha del Pedido</span>
                                <span class="text-2xl font-extrabold text-black mt-1">{{ $pedido->created_at->format('d/m/Y') }}</span>
                                <span class="text-xs font-semibold text-gray-500 mb-4">{{ $pedido->created_at->format('h:i A') }}</span>
                               @if($pedido->estado == 'pendiente')
    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded font-bold text-[10px] uppercase shadow-sm">EN REVISIÓN</span>
@elseif($pedido->estado == 'pendiente_recogida')
    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded font-bold text-[10px] uppercase shadow-sm">POR RECOGER</span>
@elseif($pedido->estado == 'entregado')
    <span class="bg-green-100 text-green-800 px-3 py-1 rounded font-bold text-[10px] uppercase shadow-sm">ENTREGADO</span>
@else
    <span class="bg-red-100 text-red-800 px-3 py-1 rounded font-bold text-[10px] uppercase shadow-sm">CANCELADO</span>
@endif
                            </div>

                            {{-- BLOQUE DETALLES Y PRODUCTOS --}}
                            <div class="p-6 md:w-2/3 flex flex-col justify-between bg-white">
                                <div>
                                    <h3 class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-3">Productos Solicitados:</h3>
                                    <ul class="text-sm text-gray-800 space-y-2">
                                        @foreach($pedido->products as $producto)
                                            <li class="flex justify-between items-center border-b border-gray-50 pb-2">
                                                <span><span class="font-bold">{{ $producto->pivot->cantidad }}x</span> {{ $producto->nombre }}</span>
                                                <span class="text-gray-500 font-semibold">${{ number_format($producto->pivot->precio_historico * $producto->pivot->cantidad, 0, ',', '.') }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Precio Total y Botón Cancelar --}}
                                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <div>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider block">Total Pedido:</span>
                                        <span class="text-xl font-extrabold text-[#D4AF37]">${{ number_format($pedido->total, 0, ',', '.') }}</span>
                                    </div>
                                    
                                    {{-- Solo permite cancelar si sigue pendiente --}}
                                    @if(in_array($pedido->estado, ['pendiente', 'pendiente_recogida']))
                                        {{-- Botón que abre el modal de cancelación para este pedido --}}
                                        <button type="button"
                                            onclick="abrirModalCancelar('{{ $pedido->id }}')"
                                            class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 font-bold py-2 px-4 rounded transition text-sm">
                                            Cancelar Pedido
                                        </button>

                                        {{-- Formulario oculto para este pedido --}}
                                        <form id="formCancelar-{{ $pedido->id }}" action="{{ route('portal.pedidos.cancelar', $pedido) }}" method="POST" class="hidden">
                                            @csrf
                                            @method('PATCH')
                                        </form>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if($pedidos->hasPages())
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        {{ $pedidos->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
    {{-- ===== MODAL DE CANCELACIÓN DE PEDIDO ===== --}}
    <div id="modalCancelarPedido" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.85);">
        <div class="bg-[#111111] rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">

            {{-- Icono de advertencia --}}
            <div class="flex justify-center mb-5">
                <div class="bg-red-600 rounded-full p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
            </div>

            {{-- Título --}}
            <h2 class="text-2xl font-extrabold text-white uppercase tracking-wide mb-3">¿Cancelar tu pedido?</h2>

            {{-- Descripción --}}
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                Si cancelas ahora, perderás la reserva de los productos seleccionados y el equipo de
                <span class="text-[#D4AF37] font-semibold">JyM Barbería</span>
                será notificado del cambio.<br><br>
                <span class="text-red-400 font-semibold">Esta acción no se puede deshacer.</span>
            </p>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                {{-- Mantener pedido --}}
                <button type="button" onclick="cerrarModalCancelar()"
                    class="flex-1 py-3 px-6 rounded-full font-bold text-gray-400 hover:text-white transition uppercase tracking-wide text-sm" style="background:#222;">
                    Conservar Pedido
                </button>
                {{-- Confirmar cancelación --}}
                <button type="button" id="btnConfirmarCancelacion"
                    class="flex-1 py-3 px-6 rounded-full font-extrabold text-white bg-red-600 hover:bg-red-700 transition uppercase tracking-wide text-sm shadow-lg">
                    Sí, cancelar pedido
                </button>
            </div>
        </div>
    </div>
    {{-- ===== FIN MODAL ===== --}}

    <script>
        let pedidoIdActivo = null;

        function abrirModalCancelar(pedidoId) {
            pedidoIdActivo = pedidoId;
            document.getElementById('modalCancelarPedido').classList.remove('hidden');
        }

        function cerrarModalCancelar() {
            pedidoIdActivo = null;
            document.getElementById('modalCancelarPedido').classList.add('hidden');
        }

        document.getElementById('btnConfirmarCancelacion').addEventListener('click', function () {
            if (pedidoIdActivo) {
                document.getElementById('formCancelar-' + pedidoIdActivo).submit();
            }
        });
    </script>

@endsection
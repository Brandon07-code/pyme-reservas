@extends('layouts.app')

@section('title', 'Pedidos')

@section('content')
    <x-page-header title="Recepción de Pedidos (Tienda)" createRoute="" buttonText="" />

    {{-- Tarjetas KPI JyM Style --}}
    <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-widest">Resumen de Tienda (Filtros)</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('orders.index') }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 border-b-4 border-gray-600 hover:bg-black transition cursor-pointer {{ !$estadoFilter ? 'ring-2 ring-gray-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pedidos</h3>
            <p class="text-3xl font-extrabold text-white">{{ $total }}</p>
        </a>
        <a href="{{ route('orders.index', ['estado' => 'pendiente_recogida']) }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 border-b-4 border-yellow-500 hover:bg-black transition cursor-pointer {{ $estadoFilter == 'pendiente_recogida' ? 'ring-2 ring-yellow-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Por Recoger (Pendientes)</h3>
            <p class="text-3xl font-extrabold text-yellow-500">{{ $pendientes }}</p>
        </a>
        <a href="{{ route('orders.index', ['estado' => 'entregado']) }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 border-b-4 border-green-500 hover:bg-black transition cursor-pointer {{ $estadoFilter == 'entregado' ? 'ring-2 ring-green-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Entregados / Pagados</h3>
            <p class="text-3xl font-extrabold text-green-500">{{ $entregados }}</p>
        </a>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ session('success') }}</p></div> @endif

    {{-- LISTADO ESTILO E-COMMERCE --}}
    @if($orders->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-8 text-center text-gray-500 italic border border-gray-100">No se encontraron pedidos.</div>
    @else
        <div class="space-y-4">
            @foreach($orders as $pedido)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden flex flex-col md:flex-row hover:shadow-md transition">
                    
                    {{-- Bloque Info Cliente --}}
                    <div class="bg-gray-50 p-6 md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col justify-center">
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">{{ $pedido->created_at->format('d/m/Y h:i A') }}</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $pedido->client->primer_nombre }} {{ $pedido->client->primer_apellido }}</h3>
                        <p class="text-sm text-indigo-600 font-semibold mt-1">📞 {{ $pedido->client->telefono }}</p>
                    </div>

                    {{-- Bloque Info Productos --}}
                    <div class="p-6 md:w-2/3 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-xs text-gray-500 font-bold uppercase tracking-widest">Productos Solicitados</h4>
                                @if($pedido->estado == 'pendiente_recogida') <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Pendiente</span>
                                @elseif($pedido->estado == 'entregado') <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Entregado</span>
                                @else <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Cancelado</span>
                                @endif
                            </div>
                            
                            <ul class="space-y-2">
                                @foreach($pedido->products as $prod)
                                    <li class="flex justify-between items-center text-sm border-b border-gray-50 pb-2">
                                        <span class="text-gray-700"><span class="font-bold text-gray-900">{{ $prod->pivot->cantidad }}x</span> {{ $prod->nombre }}</span>
                                        <span class="text-gray-500 font-semibold">${{ number_format($prod->pivot->precio_historico * $prod->pivot->cantidad, 0, ',', '.') }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Total y Botones de Acción --}}
                        <div class="mt-6 pt-4 border-t border-gray-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                            <div>
                                <span class="text-[10px] font-bold uppercase text-gray-400 mr-2">Total a Cobrar:</span>
                                <span class="text-2xl font-extrabold text-black">${{ number_format($pedido->total, 0, ',', '.') }}</span>
                            </div>
                            
                            @if($pedido->estado == 'pendiente_recogida')
                                <div class="flex gap-2 w-full sm:w-auto">
                                    <form action="{{ route('orders.update', $pedido) }}" method="POST" onsubmit="return confirm('¿El cliente ya pagó y recogió el pedido?');" class="w-full sm:w-auto">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="entregado">
                                        <button type="submit" class="w-full bg-[#0f172a] hover:bg-black text-[#D4AF37] font-bold py-2 px-6 rounded text-xs uppercase tracking-widest shadow transition">✔ Entregar</button>
                                    </form>
                                    
                                    <form action="{{ route('orders.update', $pedido) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar? El stock volverá a la vitrina.');" class="w-full sm:w-auto">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="estado" value="cancelado">
                                        <button type="submit" class="w-full bg-red-100 hover:bg-red-600 text-red-600 hover:text-white font-bold py-2 px-6 rounded text-xs uppercase tracking-widest shadow transition border border-red-200">Cancelar</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
        
        @if($orders->hasPages()) 
            <div class="mt-6 bg-[#0f172a] rounded-lg shadow-md p-4 border border-gray-800">
                {{ $orders->appends(['estado' => $estadoFilter])->links() }}
            </div> 
        @endif
    @endif
@endsection
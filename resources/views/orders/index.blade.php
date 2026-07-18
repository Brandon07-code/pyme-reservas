@extends('layouts.app')

@section('title', 'Pedidos')

@section('content')
    <x-page-header title="Recepción de Pedidos (Tienda)" createRoute="" buttonText="" />

    {{-- Tarjetas KPI JyM Style (Ahora son 4 columnas) --}}
    <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-widest">Resumen de Tienda (Filtros)</p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('orders.index') }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 hover:bg-black transition cursor-pointer {{ !$estadoFilter ? 'ring-2 ring-[#D4AF37]' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Pedidos</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $total }}</p>
        </a>
        <a href="{{ route('orders.index', ['estado' => 'pendiente']) }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 hover:bg-black transition cursor-pointer {{ $estadoFilter == 'pendiente' ? 'ring-2 ring-[#D4AF37]' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Nuevos (Empacar)</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $nuevos }}</p>
        </a>
        <a href="{{ route('orders.index', ['estado' => 'pendiente_recogida']) }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 hover:bg-black transition cursor-pointer {{ $estadoFilter == 'pendiente_recogida' ? 'ring-2 ring-[#D4AF37]' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Por Recoger (Caja)</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $pendientes }}</p>
        </a>
        <a href="{{ route('orders.index', ['estado' => 'entregado']) }}" class="bg-[#0f172a] rounded-lg shadow-lg p-5 hover:bg-black transition cursor-pointer {{ $estadoFilter == 'entregado' ? 'ring-2 ring-[#D4AF37]' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Entregados</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $entregados }}</p>
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
                    <div class="bg-gray-50 p-6 md:w-1/3 border-b md:border-b-0 md:border-r border-gray-200 flex flex-col justify-center relative">
                        @if($pedido->client->user && $pedido->client->user->avatar)
                            <img src="{{ $pedido->client->user->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border-2 border-[#D4AF37] absolute top-4 right-4 shadow-sm">
                        @endif
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">{{ $pedido->created_at->format('d/m/Y h:i A') }}</p>
                        <h3 class="text-lg font-bold text-gray-900">{{ $pedido->client->primer_nombre }} {{ $pedido->client->primer_apellido }}</h3>
                        <p class="text-sm text-indigo-600 font-semibold mt-1">📞 {{ $pedido->client->telefono }}</p>
                    </div>

                    {{-- Bloque Info Productos --}}
                    <div class="p-6 md:w-2/3 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <h4 class="text-xs text-gray-500 font-bold uppercase tracking-widest">Productos Solicitados</h4>
                                
                                {{-- Badges de Estado Mejorados --}}
                                @if($pedido->estado == 'pendiente') <span class="bg-blue-100 text-blue-800 border border-blue-200 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm animate-pulse">¡NUEVO! Por Empacar</span>
                                @elseif($pedido->estado == 'pendiente_recogida') <span class="bg-yellow-100 text-yellow-800 border border-yellow-200 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Listo en Caja</span>
                                @elseif($pedido->estado == 'entregado') <span class="bg-green-100 text-green-800 border border-green-200 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Entregado</span>
                                @else <span class="bg-red-100 text-red-800 border border-red-200 px-3 py-1 rounded-full font-bold text-[10px] uppercase shadow-sm">Cancelado</span>
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
                        <div class="flex gap-2 w-full sm:w-auto">
    
    {{-- 1. Si está RECIÉN COMPRADO ('pendiente'), el admin debe EMPACAR --}}
    @if($pedido->estado == 'pendiente')
        <form action="{{ route('orders.update', $pedido) }}" method="POST">
            @csrf @method('PUT')
            <input type="hidden" name="estado" value="pendiente_recogida">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded text-xs uppercase tracking-widest shadow transition">
                📦 EMPACAR
            </button>
        </form>
    @endif

    {{-- 2. Si ya lo empacó ('pendiente_recogida'), el admin cobra y ENTREGA --}}
    @if($pedido->estado == 'pendiente_recogida')
        <form action="{{ route('orders.update', $pedido) }}" method="POST" onsubmit="return confirm('¿El cliente ya pagó y recogió el pedido?');">
            @csrf @method('PUT')
            <input type="hidden" name="estado" value="entregado">
            <button type="submit" class="bg-[#0f172a] hover:bg-black text-white font-bold py-2 px-6 rounded text-xs uppercase tracking-widest shadow transition flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-[#D4AF37]" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                ENTREGAR
            </button>
        </form>
    @endif

    {{-- 3. Botón de CANCELAR (Solo visible si no se ha entregado) --}}
    @if(in_array($pedido->estado, ['pendiente', 'pendiente_recogida']))
        <form action="{{ route('orders.update', $pedido) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas cancelar? El stock volverá a la vitrina.');">
            @csrf @method('PUT')
            <input type="hidden" name="estado" value="cancelado">
            <button type="submit" class="bg-red-50 hover:bg-red-600 text-red-600 hover:text-white font-bold py-2 px-6 rounded text-xs uppercase tracking-widest shadow transition border border-red-200">
                CANCELAR
            </button>
        </form>
    @endif

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
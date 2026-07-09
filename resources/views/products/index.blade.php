@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    <x-page-header title="Gestión de Productos" createRoute="{{ route('productos.create') }}" buttonText="+ Nuevo Producto" />

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Productos</h3><p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Activos</h3><p class="text-3xl font-bold text-green-600">{{ $activos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Inactivos</h3><p class="text-3xl font-bold text-red-600">{{ $inactivos }}</p>
        </div>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p>{{ session('success') }}</p></div> @endif

    <form method="GET" action="{{ route('productos.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar perfume o marca..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search) <a href="{{ route('productos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a> @endif
    </form>

    {{-- GALERÍA DE TARJETAS (REEMPLAZA A LA TABLA) --}}
    @if($products->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-8 text-center text-gray-500">No se encontraron productos en el inventario.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $producto)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition">
                    {{-- Imagen --}}
                    <div class="h-56 w-full bg-gray-100 relative">
                        <img src="{{ $producto->imagen_url ? asset($producto->imagen_url) : 'https://via.placeholder.com/400x400?text=Sin+Imagen' }}" 
                             alt="{{ $producto->nombre }}" 
                             class="w-full h-full object-contain p-2"
                             onerror="this.src='https://via.placeholder.com/400x400?text=Sin+Imagen'">
                        <div class="absolute top-2 right-2">
                            <x-status-badge :estado="$producto->estado" />
                        </div>
                    </div>
                    
                    {{-- Detalles del Perfume --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <p class="text-xs text-indigo-600 font-bold uppercase tracking-wider mb-1">{{ $producto->marca ?? 'Sin marca' }}</p>
                        <h2 class="text-lg font-bold text-gray-800 leading-tight mb-2">{{ $producto->nombre }}</h2>
                        <div class="flex items-center space-x-2 text-xs text-gray-500 mb-4">
                            <span class="bg-gray-100 px-2 py-1 rounded">{{ $producto->genero ?? 'Unisex' }}</span>
                            <span class="bg-gray-100 px-2 py-1 rounded">{{ $producto->category->nombre ?? 'N/A' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-end mt-auto pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-xl font-bold text-gray-900">${{ number_format($producto->precio, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-semibold uppercase text-gray-500">Stock</p>
                                <p class="font-bold text-lg {{ $producto->stock_actual <= 5 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $producto->stock_actual }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 flex justify-end">
                        <x-action-buttons editRoute="{{ route('productos.edit', $producto) }}" destroyRoute="{{ route('productos.destroy', $producto) }}" :estado="$producto->estado" />
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($products->hasPages())
            <div class="mt-6">{{ $products->appends(['search' => $search])->links() }}</div>
        @endif
    @endif
@endsection
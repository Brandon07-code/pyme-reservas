@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <x-page-header title="Gestión de Servicios" createRoute="{{ route('servicios.create') }}" buttonText="+ Nuevo Servicio" />

    {{-- Tarjetas KPI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Servicios</h3><p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Activos</h3><p class="text-3xl font-bold text-green-600">{{ $activos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Inactivos</h3><p class="text-3xl font-bold text-red-600">{{ $inactivos }}</p>
        </div>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p>{{ session('success') }}</p></div> @endif

    <form method="GET" action="{{ route('servicios.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar servicio o categoría..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search) <a href="{{ route('servicios.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a> @endif
    </form>

    {{-- GALERÍA DE TARJETAS (REEMPLAZA A LA TABLA) --}}
    @if($services->isEmpty())
        <div class="bg-white shadow-md rounded-lg p-8 text-center text-gray-500">No se encontraron servicios en el catálogo.</div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($services as $servicio)
                <div class="bg-white rounded-lg shadow-md overflow-hidden flex flex-col hover:shadow-lg transition">
                    {{-- Espacio para la imagen --}}
                    <div class="h-48 w-full bg-gray-200 relative">
                        <img src="{{ $servicio->imagen_url ? asset($servicio->imagen_url) : 'https://via.placeholder.com/400x300?text=Sin+Imagen' }}" 
                             alt="{{ $servicio->nombre }}" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://via.placeholder.com/400x300?text=Sin+Imagen'">
                        <div class="absolute top-2 right-2">
                            <x-status-badge :estado="$servicio->estado" />
                        </div>
                    </div>
                    
                    {{-- Cuerpo de la tarjeta --}}
                    <div class="p-4 flex-1 flex flex-col">
                        <h2 class="text-lg font-bold text-gray-800 truncate" title="{{ $servicio->nombre }}">{{ $servicio->nombre }}</h2>
                        <p class="text-xs text-indigo-600 font-semibold mb-2 uppercase">{{ $servicio->category->nombre ?? 'N/A' }}</p>
                        
                        <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-100">
                            <div>
                                <p class="text-xl font-bold text-gray-900">${{ number_format($servicio->precio, 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500">⏱ {{ $servicio->duracion_minutos }} min</p>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones (Botones integrados) --}}
                    <div class="bg-gray-50 px-4 py-3 border-t border-gray-100 flex justify-end">
                        <x-action-buttons editRoute="{{ route('servicios.edit', $servicio) }}" destroyRoute="{{ route('servicios.destroy', $servicio) }}" :estado="$servicio->estado" />
                    </div>
                </div>
            @endforeach
        </div>
        
        @if($services->hasPages())
            <div class="mt-6">{{ $services->appends(['search' => $search])->links() }}</div>
        @endif
    @endif
@endsection
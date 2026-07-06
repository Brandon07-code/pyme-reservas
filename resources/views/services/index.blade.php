@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <x-page-header 
        title="Gestión de Servicios" 
        createRoute="{{ route('servicios.create') }}" 
        buttonText="+ Nuevo Servicio" 
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Servicios</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Activos</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Inactivos</h3>
            <p class="text-3xl font-bold text-red-600">{{ $inactivos }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form method="GET" action="{{ route('servicios.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar servicio o categoría..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search)
            <a href="{{ route('servicios.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a>
        @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $servicio)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $servicio->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $servicio->category->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($servicio->precio, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $servicio->duracion_minutos }} min</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-status-badge :estado="$servicio->estado" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons 
                                editRoute="{{ route('servicios.edit', $servicio) }}" 
                                destroyRoute="{{ route('servicios.destroy', $servicio) }}" 
                                :estado="$servicio->estado"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron servicios.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($services->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $services->appends(['search' => $search])->links() }}
            </div>
        @endif
    </div>
@endsection
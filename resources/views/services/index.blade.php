@extends('layouts.app')

@section('title', 'Servicios')

@section('content')
    <!-- Componente de Encabezado -->
    <x-page-header 
        title="Gestión de Servicios" 
        createRoute="{{ route('servicios.create') }}" 
        buttonText="+ Nuevo Servicio" 
    />

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

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
                            <!-- Componente de Estado -->
                            <x-status-badge :estado="$servicio->estado" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <!-- Componente de Botones -->
                            <x-action-buttons 
                                editRoute="{{ route('servicios.edit', $servicio) }}" 
                                destroyRoute="{{ route('servicios.destroy', $servicio) }}" 
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay servicios registrados en el sistema.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($services->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $services->links() }}
            </div>
        @endif
    </div>
@endsection
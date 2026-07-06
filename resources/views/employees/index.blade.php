@extends('layouts.app')

@section('title', 'Empleados')

@section('content')
    <x-page-header 
        title="Gestión de Empleados" 
        createRoute="{{ route('empleados.create') }}" 
        buttonText="+ Nuevo Empleado" 
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $empleado)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $empleado->user->primer_nombre }} {{ $empleado->user->primer_apellido }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empleado->especialidad ?? 'No definida' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empleado->telefono ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-status-badge :estado="$empleado->estado" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons 
                                editRoute="{{ route('empleados.edit', $empleado) }}" 
                                destroyRoute="{{ route('empleados.destroy', $empleado) }}" 
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay empleados registrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($employees->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $employees->links() }}
            </div>
        @endif
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Clientes')

@section('content')
    <x-page-header title="Gestión de Clientes" createRoute="{{ route('clientes.create') }}" buttonText="+ Nuevo Cliente" />

    {{-- Tarjetas de Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Clientes</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $totalClients }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Clientes Activos</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activeClients }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Clientes Inactivos</h3>
            <p class="text-3xl font-bold text-red-600">{{ $inactiveClients }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    {{-- Filtro de Búsqueda --}}
    <form method="GET" action="{{ route('clientes.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, teléfono o email..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search)
            <a href="{{ route('clientes.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a>
        @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre Completo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Teléfono</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($clients as $cliente)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $cliente->primer_nombre }} {{ $cliente->primer_apellido }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cliente->telefono }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cliente->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm">
                            <x-status-badge :estado="$cliente->estado" />
                        </td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <x-action-buttons editRoute="{{ route('clientes.edit', $cliente) }}" destroyRoute="{{ route('clientes.destroy', $cliente) }}" :estado="$cliente->estado" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No se encontraron clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
        
        @if($clients->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $clients->appends(['search' => $search])->links() }}
            </div>
        @endif
    </div>
@endsection
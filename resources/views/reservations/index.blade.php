@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <x-page-header title="Gestión de Reservas" createRoute="{{ route('reservas.create') }}" buttonText="+ Nueva Reserva" />

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-800">
            <h3 class="text-gray-500 text-xs font-semibold uppercase">Total</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-xs font-semibold uppercase">Pendientes</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $pendientes }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-xs font-semibold uppercase">Confirmadas</h3>
            <p class="text-2xl font-bold text-blue-600">{{ $confirmadas }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-xs font-semibold uppercase">Completadas</h3>
            <p class="text-2xl font-bold text-green-600">{{ $completadas }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-xs font-semibold uppercase">Canceladas</h3>
            <p class="text-2xl font-bold text-red-600">{{ $canceladas }}</p>
        </div>
    </div>
    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p>{{ session('success') }}</p></div> @endif

    
    <form method="GET" action="{{ route('reservas.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por cliente o barbero..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search) <a href="{{ route('reservas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a> @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha y Hora</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado (Barbero)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($reservations as $reserva)
                    <tr>
                        <!-- LAS COLUMNAS QUE SE HABÍAN BORRADO -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} <br>
                            <span class="text-xs text-gray-500 font-normal">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->client->primer_nombre }} {{ $reserva->client->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->employee->user->primer_nombre }} {{ $reserva->employee->user->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($reserva->total, 0, ',', '.') }}</td>
                        
                        <!-- EL ESTADO CON LOS BADGES -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($reserva->estado == 'pendiente') <span class="px-2 text-[10px] uppercase font-bold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @elseif($reserva->estado == 'confirmada') <span class="px-2 text-[10px] uppercase font-bold rounded-full bg-blue-100 text-blue-800">Confirmada</span>
                            @elseif($reserva->estado == 'completada') <span class="px-2 text-[10px] uppercase font-bold rounded-full bg-green-100 text-green-800">Completada</span>
                            @elseif($reserva->estado == 'cancelada') <span class="px-2 text-[10px] uppercase font-bold rounded-full bg-red-100 text-red-800">Cancelada</span>
                            @else <span class="px-2 text-[10px] uppercase font-bold rounded-full bg-gray-200 text-gray-800">No Asistió</span>
                            @endif
                        </td>
                        
                        <!-- LAS ACCIONES (Seguridad Horizontal Aplicada) -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end space-x-2">
                            {{-- Solo mostramos el botón de Editar si la cita está Activa (Candado de Historial) --}}
                            @if(in_array($reserva->estado, ['pendiente', 'confirmada']))
                                <a href="{{ route('reservas.edit', $reserva) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition">Editar</a>
                            @endif
                            
                            {{-- Solo el Administrador puede Eliminar físicamente (Seguridad RBAC) --}}
                            @if(Auth::user()->role_id == 1)
                                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas ELIMINAR esta reserva permanentemente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay reservas programadas.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reservations->hasPages()) <div class="px-6 py-3 bg-gray-50 border-t">{{ $reservations->appends(['search' => $search])->links() }}</div> @endif
    </div>
@endsection
@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <x-page-header title="Gestión de Reservas" createRoute="{{ route('reservas.create') }}" buttonText="+ Nueva Reserva" />

    {{-- Tarjetas KPI Clickeables (Mes Actual) --}}
    <p class="text-sm text-gray-500 mb-2 font-semibold">Resumen del Mes (Clic para filtrar)</p>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('reservas.index') }}" class="bg-white rounded-lg shadow p-4 border-l-4 border-gray-800 hover:bg-gray-50 transition cursor-pointer {{ !$estadoFilter ? 'ring-2 ring-gray-400' : '' }}">
            <h3 class="text-gray-500 text-[10px] font-semibold uppercase">Total (Mes)</h3><p class="text-2xl font-bold text-gray-800">{{ $total }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'pendiente']) }}" class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500 hover:bg-yellow-50 transition cursor-pointer {{ $estadoFilter == 'pendiente' ? 'ring-2 ring-yellow-400' : '' }}">
            <h3 class="text-gray-500 text-[10px] font-semibold uppercase">Pendientes</h3><p class="text-2xl font-bold text-yellow-600">{{ $pendientes }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'confirmada']) }}" class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500 hover:bg-blue-50 transition cursor-pointer {{ $estadoFilter == 'confirmada' ? 'ring-2 ring-blue-400' : '' }}">
            <h3 class="text-gray-500 text-[10px] font-semibold uppercase">Confirmadas</h3><p class="text-2xl font-bold text-blue-600">{{ $confirmadas }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'completada']) }}" class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500 hover:bg-green-50 transition cursor-pointer {{ $estadoFilter == 'completada' ? 'ring-2 ring-green-400' : '' }}">
            <h3 class="text-gray-500 text-[10px] font-semibold uppercase">Completadas</h3><p class="text-2xl font-bold text-green-600">{{ $completadas }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'cancelada']) }}" class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500 hover:bg-red-50 transition cursor-pointer {{ $estadoFilter == 'cancelada' ? 'ring-2 ring-red-400' : '' }}">
            <h3 class="text-gray-500 text-[10px] font-semibold uppercase">Canceladas</h3><p class="text-2xl font-bold text-red-600">{{ $canceladas }}</p>
        </a>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p>{{ session('success') }}</p></div> @endif
    @if($errors->any()) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm"><p>{{ $errors->first() }}</p></div> @endif

    {{-- Filtro de Búsqueda --}}
    <form method="GET" action="{{ route('reservas.index') }}" class="mb-6 flex gap-2">
        @if($estadoFilter) <input type="hidden" name="estado" value="{{ $estadoFilter }}"> @endif
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por cliente o barbero..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search) <a href="{{ route('reservas.index', ['estado' => $estadoFilter]) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a> @endif
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
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} <br>
                            <span class="text-xs text-gray-500 font-normal">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->client->primer_nombre }} {{ $reserva->client->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->employee->user->primer_nombre }} {{ $reserva->employee->user->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($reserva->total, 0, ',', '.') }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($reserva->estado == 'pendiente') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200">Pendiente</span>
                            @elseif($reserva->estado == 'confirmada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-200">Confirmada</span>
                            @elseif($reserva->estado == 'completada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-green-100 text-green-800 border border-green-200">Completada</span>
                            @elseif($reserva->estado == 'cancelada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-red-100 text-red-800 border border-red-200">Cancelada</span>
                            @else <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-gray-200 text-gray-800 border border-gray-300">No Asistió</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center space-x-2">
                            
                               @php
    $fecha = \Carbon\Carbon::parse($reserva->fecha)->format('Y-m-d');

    $fechaHoraFin = \Carbon\Carbon::createFromFormat(
        'Y-m-d H:i:s',
        $fecha . ' ' . $reserva->hora_fin
    );

    $puedeCompletarse = now()->greaterThanOrEqualTo($fechaHoraFin);
@endphp 
                            
                            {{-- Solo pintamos el boton Verde si está activa y el tiempo YA PASÓ --}}
                            @if(in_array($reserva->estado, ['pendiente', 'confirmada']) && $puedeCompletarse)
                                <form action="{{ route('reservas.completar', $reserva) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Marcar como Completada" class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition shadow-sm border border-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            @endif
                            
                            @if(in_array($reserva->estado, ['pendiente', 'confirmada']))
                                <a href="{{ route('reservas.edit', $reserva) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition">Editar</a>
                            @endif
                            
                            @if(Auth::user()->role_id == 1)
                                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas ELIMINAR esta reserva permanentemente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500 italic">No hay reservas con este filtro.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reservations->hasPages()) <div class="px-6 py-3 bg-gray-50 border-t">{{ $reservations->appends(['search' => $search, 'estado' => $estadoFilter])->links() }}</div> @endif
    </div>
@endsection
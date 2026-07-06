@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <x-page-header title="Gestión de Reservas" createRoute="{{ route('reservas.create') }}" buttonText="+ Nueva Reserva" />

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} <br>
                            <span class="text-xs text-gray-500 font-normal">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->client->primer_nombre }} {{ $reserva->client->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $reserva->employee->user->primer_nombre }} {{ $reserva->employee->user->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($reserva->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($reserva->estado == 'pendiente') <span class="px-2 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pendiente</span>
                            @elseif($reserva->estado == 'confirmada') <span class="px-2 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Confirmada</span>
                            @elseif($reserva->estado == 'completada') <span class="px-2 text-xs font-semibold rounded-full bg-green-100 text-green-800">Completada</span>
                            @else <span class="px-2 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelada</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons editRoute="{{ route('reservas.edit', $reserva) }}" destroyRoute="{{ route('reservas.destroy', $reserva) }}" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay reservas programadas.</td></tr>
                @endforelse
            </tbody>
        </table>
        @if($reservations->hasPages()) <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">{{ $reservations->links() }}</div> @endif
    </div>
@endsection
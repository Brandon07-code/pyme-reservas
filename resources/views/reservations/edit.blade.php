@extends('layouts.app')
@section('title', 'Gestionar Estado Reserva')
@section('content')
    <div class="max-w-2xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Actualizar Estado de Reserva</h1>
        <div class="bg-white shadow-md rounded-lg p-8 mb-6">
            <h3 class="font-bold text-lg border-b pb-2 mb-4">Detalles de la Cita</h3>
            <p><strong>Cliente:</strong> {{ $reserva->client->primer_nombre }} {{ $reserva->client->primer_apellido }}</p>
            <p><strong>Barbero:</strong> {{ $reserva->employee->user->primer_nombre }} {{ $reserva->employee->user->primer_apellido }}</p>
            <p><strong>Fecha y Hora:</strong> {{ $reserva->fecha }} a las {{ $reserva->hora_inicio }}</p>
            <p><strong>Total:</strong> ${{ number_format($reserva->total, 0, ',', '.') }}</p>
        </div>
        <form action="{{ route('reservas.update', $reserva) }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf @method('PUT')
            <label class="block text-sm font-medium text-gray-700 mb-1">Cambiar Estado a:</label>
            @php
                $fechaHoraFin = \Carbon\Carbon::parse($reserva->fecha . ' ' . $reserva->hora_fin);
                $yaPaso = now()->greaterThanOrEqualTo($fechaHoraFin);
            @endphp
            <select name="estado" class="w-full border-gray-300 rounded-md shadow-sm border p-2 mb-4">
                @if(!$yaPaso)
                    <option value="pendiente" {{ $reserva->estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ $reserva->estado == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                @else
                    <option value="completada" {{ $reserva->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="no_asistio" {{ $reserva->estado == 'no_asistio' ? 'selected' : '' }}>No Asistió</option>
                @endif
                <option value="cancelada" {{ $reserva->estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 rounded">Actualizar Estado</button>
        </form>
    </div>
@endsection
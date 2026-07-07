@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Agendar Reserva</h1>
            <a href="{{ route('reservas.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('reservas.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Cliente -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                    <select name="client_id" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione un cliente</option>
                        @foreach($clients as $client) 
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->primer_nombre }} {{ $client->primer_apellido }} - {{ $client->telefono }}
                            </option> 
                        @endforeach
                    </select>
                    @error('client_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Empleado -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Empleado (Barbero) *</label>
                    <select name="employee_id" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione a quién atenderá</option>
                        @foreach($employees as $emp) 
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->user->primer_nombre }} {{ $emp->user->primer_apellido }} ({{ $emp->especialidad }})
                            </option> 
                        @endforeach
                    </select>
                    @error('employee_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha y Hora -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fecha de la cita *</label>
                    <input type="date" name="fecha" value="{{ old('fecha') }}" required min="{{ date('Y-m-d') }}" class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('fecha') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hora de inicio *</label>
                    <input type="time" name="hora_inicio" value="{{ old('hora_inicio') }}" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
                    @error('hora_inicio') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Servicios Múltiples -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Servicios Deseados * (Seleccione al menos uno)</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2 p-4 border border-gray-200 rounded-md bg-gray-50">
                        @foreach($services as $servicio)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="servicios[]" value="{{ $servicio->id }}" 
                                    {{ is_array(old('servicios')) && in_array($servicio->id, old('servicios')) ? 'checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-blue-600 rounded">
                                <span class="ml-2 text-gray-700">{{ $servicio->nombre }} <span class="text-xs text-gray-500">(${{ number_format($servicio->precio, 0, ',', '.') }} - {{ $servicio->duracion_minutos }}min)</span></span>
                            </label>
                        @endforeach
                    </div>
                    @error('servicios') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">
                    Agendar Cita
                </button>
            </div>
        </form>
    </div>
@endsection
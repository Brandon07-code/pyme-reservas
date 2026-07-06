@extends('layouts.app')

@section('title', 'Editar Empleado')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Empleado</h1>
            <a href="{{ route('empleados.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('empleados.update', $empleado) }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Usuario Bloqueado -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cuenta de Usuario Asociada</label>
                    <input type="text" disabled value="{{ $empleado->user->primer_nombre }} {{ $empleado->user->primer_apellido }} ({{ $empleado->user->email }})" class="w-full border-gray-200 bg-gray-100 rounded-md shadow-sm border p-2 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">El usuario asociado no puede cambiarse. Si necesitas desvincularlo, desactiva este registro y crea uno nuevo.</p>
                </div>

                <!-- Especialidad -->
                <div>
                    <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad', $empleado->especialidad) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('especialidad') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono', $empleado->telefono) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('telefono') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección de Residencia</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion', $empleado->direccion) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('direccion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="estado" id="estado" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="1" {{ old('estado', $empleado->estado) == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $empleado->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                    Actualizar Empleado
                </button>
            </div>
        </form>
    </div>
@endsection
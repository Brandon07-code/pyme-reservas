@extends('layouts.app')

@section('title', 'Nuevo Empleado' )

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Empleado</h1>
            <a href="{{ route('empleados.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('empleados.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Usuario Asociado -->
                <div class="md:col-span-2">
                    <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Vincular con Cuenta de Usuario *</label>
                    <select name="user_id" id="user_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="">Seleccione un usuario activo</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->primer_nombre }} {{ $user->primer_apellido }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                    <p class="text-xs text-gray-500 mt-1">Solo se muestran usuarios sin perfil de empleado asignado.</p>
                </div>

                <!-- Especialidad -->
                <div>
                    <label for="especialidad" class="block text-sm font-medium text-gray-700 mb-1">Especialidad</label>
                    <input type="text" name="especialidad" id="especialidad" value="{{ old('especialidad') }}" placeholder="Ej: Barbero Senior" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('especialidad') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Teléfono -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('telefono') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Dirección -->
                <div class="md:col-span-2">
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección de Residencia</label>
                    <input type="text" name="direccion" id="direccion" value="{{ old('direccion') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('direccion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="estado" id="estado" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded shadow">
                    Guardar Empleado
                </button>
            </div>
        </form>
    </div>
@endsection
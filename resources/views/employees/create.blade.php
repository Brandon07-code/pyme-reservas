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
                <!-- Nombres y Apellidos -->
                <div>
                    <label for="primer_nombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" value="{{ old('primer_nombre') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('primer_nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="primer_apellido" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido *</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" value="{{ old('primer_apellido') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('primer_apellido') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Credenciales -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email de Acceso *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                    <input type="password" name="password" id="password" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2" minlength="8">
                    @error('password') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Rol -->
                <div class="md:col-span-2">
                    <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Rol en el Sistema *</label>
                    <select name="role_id" id="role_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="2" {{ old('role_id') == '2' ? 'selected' : '' }}>Empleado (Barbero)</option>
                        <option value="1" {{ old('role_id') == '1' ? 'selected' : '' }}>Administrador</option>
                    </select>
                    @error('role_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
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
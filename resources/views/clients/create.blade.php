@extends('layouts.app')

@section('title', 'Nuevo Cliente')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Cliente</h1>
            <a href="{{ route('clientes.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('clientes.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nombres -->
                <div>
                    <label for="primer_nombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
                    <input type="text" name="primer_nombre" id="primer_nombre" value="{{ old('primer_nombre') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('primer_nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="segundo_nombre" class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" id="segundo_nombre" value="{{ old('segundo_nombre') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('segundo_nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Apellidos -->
                <div>
                    <label for="primer_apellido" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido *</label>
                    <input type="text" name="primer_apellido" id="primer_apellido" value="{{ old('primer_apellido') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('primer_apellido') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="segundo_apellido" class="block text-sm font-medium text-gray-700 mb-1">Segundo Apellido</label>
                    <input type="text" name="segundo_apellido" id="segundo_apellido" value="{{ old('segundo_apellido') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('segundo_apellido') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Contacto y Estado -->
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono *</label>
                    <input type="text" name="telefono" id="telefono" value="{{ old('telefono') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('telefono') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('email') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
                    <input type="password" name="password" id="password" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2" minlength="8">
                    @error('password') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                
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
                    Guardar Cliente
                </button>
            </div>
        </form>
    </div>
@endsection
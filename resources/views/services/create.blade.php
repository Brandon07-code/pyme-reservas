@extends('layouts.app')

@section('title', 'Nuevo Servicio')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Servicio</h1>
            <a href="{{ route('servicios.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('servicios.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nombre y Categoría -->
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Servicio *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}" required class="w-full border-gray-300 rounded-md shadow-sm border p-2">
                    @error('nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="service_category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="service_category_id" id="service_category_id" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 bg-white">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('service_category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                        @endforeach
                    </select>
                    @error('service_category_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Precio y Duración -->
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio (COP) *</label>
                    <input type="number" step="100" name="precio" id="precio" value="{{ old('precio') }}" required class="w-full border-gray-300 rounded-md shadow-sm border p-2">
                    @error('precio') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="duracion_minutos" class="block text-sm font-medium text-gray-700 mb-1">Duración (Minutos) *</label>
                    <select name="duracion_minutos" id="duracion_minutos" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 bg-white">
                        <option value="15" {{ old('duracion_minutos') == '15' ? 'selected' : '' }}>15 min</option>
                        <option value="30" {{ old('duracion_minutos', '30') == '30' ? 'selected' : '' }}>30 min</option>
                        <option value="45" {{ old('duracion_minutos') == '45' ? 'selected' : '' }}>45 min</option>
                        <option value="60" {{ old('duracion_minutos') == '60' ? 'selected' : '' }}>60 min (1 hora)</option>
                        <option value="90" {{ old('duracion_minutos') == '90' ? 'selected' : '' }}>90 min (1.5 horas)</option>
                    </select>
                    @error('duracion_minutos') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Imagen URL y Estado -->
                <div>
                    <label for="imagen_url" class="block text-sm font-medium text-gray-700 mb-1">Ruta de la Imagen <span class="text-xs text-gray-400 font-normal">(Opcional)</span></label>
                    <input type="text" name="imagen_url" id="imagen_url" value="{{ old('imagen_url') }}" placeholder="ej: services/degradado.jpg" class="w-full border-gray-300 rounded-md shadow-sm border p-2">
                    @error('imagen_url') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="estado" id="estado" required class="w-full border-gray-300 rounded-md shadow-sm border p-2 bg-white">
                        <option value="1" {{ old('estado', '1') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado') == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full border-gray-300 rounded-md shadow-sm border p-2">{{ old('descripcion') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow transition">Guardar Servicio</button>
            </div>
        </form>
    </div>
@endsection
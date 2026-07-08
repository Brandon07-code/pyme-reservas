@extends('layouts.app')
@section('title', 'Nuevo Producto')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Registrar Nuevo Producto</h1>
            <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>
        <form action="{{ route('productos.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Línea 1: Nombre, Categoría, Marca -->
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full border p-2 rounded-md">
                    @error('nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Categoría *</label>
                    <select name="product_category_id" required class="w-full border p-2 rounded-md">
                        <option value="">Seleccione...</option>
                        @foreach($categories as $cat) <option value="{{ $cat->id }}">{{ $cat->nombre }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Marca (Opcional)</label>
                    <input type="text" name="marca" value="{{ old('marca') }}" placeholder="ej: Carolina Herrera" class="w-full border p-2 rounded-md">
                </div>

                <!-- Línea 2: Género, Precio, Stock -->
                <div>
                    <label class="block text-sm font-medium mb-1">Género</label>
                    <select name="genero" class="w-full border p-2 rounded-md">
                        <option value="">Sin especificar / Unisex</option>
                        <option value="Masculino">Masculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Precio (COP) *</label>
                    <input type="number" name="precio" value="{{ old('precio') }}" required class="w-full border p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Unidades (Stock) *</label>
                    <input type="number" name="stock_actual" value="{{ old('stock_actual', 0) }}" required class="w-full border p-2 rounded-md">
                </div>

                <!-- Línea 3: Imagen y Estado -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium mb-1">Ruta de la Imagen</label>
                    <input type="text" name="imagen_url" value="{{ old('imagen_url') }}" placeholder="ej: products/212vip.jpg" class="w-full border p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Estado *</label>
                    <select name="estado" required class="w-full border p-2 rounded-md">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full border p-2 rounded-md">{{ old('descripcion') }}</textarea>
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t"><button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded">Guardar Producto</button></div>
        </form>
    </div>
@endsection
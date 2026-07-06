@extends('layouts.app')

@section('title', 'Editar Producto')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar Producto: {{ $producto->nombre }}</h1>
            <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>

        <form action="{{ route('productos.update', $producto) }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Nombre -->
                <div class="md:col-span-2">
                    <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto *</label>
                    <input type="text" name="nombre" id="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('nombre') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Categoría -->
                <div>
                    <label for="product_category_id" class="block text-sm font-medium text-gray-700 mb-1">Categoría *</label>
                    <select name="product_category_id" id="product_category_id" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('product_category_id', $producto->product_category_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('product_category_id') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Estado -->
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-1">Estado *</label>
                    <select name="estado" id="estado" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2 bg-white">
                        <option value="1" {{ old('estado', $producto->estado) == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ old('estado', $producto->estado) == '0' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                    @error('estado') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Precio -->
                <div>
                    <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio (COP) *</label>
                    <input type="number" step="100" name="precio" id="precio" value="{{ old('precio', $producto->precio) }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('precio') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Stock Actual -->
                <div>
                    <label for="stock_actual" class="block text-sm font-medium text-gray-700 mb-1">Unidades en Inventario *</label>
                    <input type="number" name="stock_actual" id="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" required class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">
                    @error('stock_actual') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2">
                    <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                    <textarea name="descripcion" id="descripcion" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border p-2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                    @error('descripcion') <span class="text-red-500 text-xs italic">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                    Actualizar Producto
                </button>
            </div>
        </form>
    </div>
@endsection
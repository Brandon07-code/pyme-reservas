@extends('layouts.app')
@section('title', 'Editar Producto')
@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Editar: {{ $producto->nombre }}</h1>
            <a href="{{ route('productos.index') }}" class="text-gray-600 hover:text-gray-900 underline">Volver al listado</a>
        </div>
        <form action="{{ route('productos.update', $producto) }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre *</label>
                    <input type="text" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required class="w-full border p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Categoría *</label>
                    <select name="product_category_id" required class="w-full border p-2 rounded-md">
                        @foreach($categories as $cat) <option value="{{ $cat->id }}" {{ $producto->product_category_id == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Marca (Opcional)</label>
                    <input type="text" name="marca" value="{{ old('marca', $producto->marca) }}" class="w-full border p-2 rounded-md">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Género</label>
                    <select name="genero" class="w-full border p-2 rounded-md">
                        <option value="" {{ $producto->genero == '' ? 'selected' : '' }}>Unisex</option>
                        <option value="Masculino" {{ $producto->genero == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                        <option value="Femenino" {{ $producto->genero == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Precio (COP) *</label>
                    <input type="number" name="precio" value="{{ old('precio', (int)$producto->precio) }}" required class="w-full border p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Unidades *</label>
                    <input type="number" name="stock_actual" value="{{ old('stock_actual', $producto->stock_actual) }}" required class="w-full border p-2 rounded-md">
                </div>

                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium mb-1">Ruta de la Imagen</label>
                    <input type="text" name="imagen_url" value="{{ old('imagen_url', $producto->imagen_url) }}" class="w-full border p-2 rounded-md">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Estado *</label>
                    <select name="estado" required class="w-full border p-2 rounded-md">
                        <option value="1" {{ $producto->estado ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$producto->estado ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>

                <div class="md:col-span-2 lg:col-span-3">
                    <label class="block text-sm font-medium mb-1">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full border p-2 rounded-md">{{ old('descripcion', $producto->descripcion) }}</textarea>
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t"><button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded">Actualizar Producto</button></div>
        </form>
    </div>
@endsection
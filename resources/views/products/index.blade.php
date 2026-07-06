@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    <x-page-header 
        title="Gestión de Productos" 
        createRoute="{{ route('productos.create') }}" 
        buttonText="+ Nuevo Producto" 
    />

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categoría</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $producto)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $producto->category->nombre ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${{ number_format($producto->precio, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $producto->stock_actual <= 5 ? 'text-red-600' : 'text-gray-600' }}">
                            {{ $producto->stock_actual }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <x-status-badge :estado="$producto->estado" />
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons 
                                editRoute="{{ route('productos.edit', $producto) }}" 
                                destroyRoute="{{ route('productos.destroy', $producto) }}" 
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay productos registrados en el inventario.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($products->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $products->links() }}
            </div>
        @endif
    </div>
@endsection
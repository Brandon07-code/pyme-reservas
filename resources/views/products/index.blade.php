@extends('layouts.app')

@section('title', 'Productos')

@section('content')
    <x-page-header 
        title="Gestión de Productos" 
        createRoute="{{ route('productos.create') }}" 
        buttonText="+ Nuevo Producto" 
    />

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Total Productos</h3>
            <p class="text-3xl font-bold text-gray-800">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Activos</h3>
            <p class="text-3xl font-bold text-green-600">{{ $activos }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-sm font-semibold uppercase">Inactivos</h3>
            <p class="text-3xl font-bold text-red-600">{{ $inactivos }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p>{{ session('success') }}</p>
        </div>
    @endif

    <form method="GET" action="{{ route('productos.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar producto o categoría..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded shadow">Buscar</button>
        @if($search)
            <a href="{{ route('productos.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded shadow">Limpiar</a>
        @endif
    </form>

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
                                :estado="$producto->estado"
                            />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No se encontraron productos.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        @if($products->hasPages())
            <div class="px-6 py-3 bg-gray-50 border-t border-gray-200">
                {{ $products->appends(['search' => $search])->links() }}
            </div>
        @endif
    </div>
@endsection
@props(['editRoute', 'destroyRoute', 'estado' => 1])

<div class="flex justify-end space-x-2">
    <a href="{{ $editRoute }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition">Editar</a>
    
    <form action="{{ $destroyRoute }}" method="POST" onsubmit="return confirm('¿Seguro que deseas {{ $estado ? 'desactivar' : 'activar' }} este registro?');">
        @csrf
        @method('DELETE')
        @if($estado)
            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition shadow-sm">
                Desactivar
            </button>
        @else
            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded transition shadow-sm">
                Activar
            </button>
        @endif
    </form>
</div>
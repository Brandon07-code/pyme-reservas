@props(['editRoute', 'destroyRoute'])

<div class="flex justify-end space-x-2">
    <a href="{{ $editRoute }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition">Editar</a>
    
    <form action="{{ $destroyRoute }}" method="POST" onsubmit="return confirm('¿Seguro que deseas desactivar este registro?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition">Desactivar</button>
    </form>
</div>
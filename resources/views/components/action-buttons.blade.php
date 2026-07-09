@props(['editRoute', 'destroyRoute', 'estado' => 1, 'scheduleRoute' => null])

<div class="flex justify-end items-center space-x-2">

    {{-- NUEVO: Botón de Horarios (Opcional, solo si se envía la ruta) --}}
    @if($scheduleRoute && in_array(Auth::user()->role_id, [1]))
        <a href="{{ $scheduleRoute }}" title="Gestionar Horarios" class="text-amber-600 hover:text-amber-900 bg-amber-100 hover:bg-amber-200 px-3 py-1 rounded transition shadow-sm font-semibold">
            ⏱ Turnos
        </a>
    @endif

    <a href="{{ $editRoute }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition shadow-sm font-semibold">Editar</a>
    
    <form action="{{ $destroyRoute }}" method="POST" onsubmit="return confirm('¿Seguro que deseas {{ $estado ? 'desactivar' : 'activar' }} este registro?');" class="inline">
        @csrf
        @method('DELETE')
        @if($estado)
            <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded transition shadow-sm font-semibold">
                Desactivar
            </button>
        @else
            <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-3 py-1 rounded transition shadow-sm font-semibold">
                Activar
            </button>
        @endif
    </form>
</div>
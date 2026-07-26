@props(['editRoute', 'destroyRoute', 'estado' => 1, 'scheduleRoute' => null, 'deletable' => false, 'showSchedule' => false])

<div class="flex justify-end items-center space-x-2">

    {{-- Botón de Horarios: solo si la ruta existe Y el empleado es barbero (role_id=2) --}}
    @if($scheduleRoute && $showSchedule && in_array(Auth::user()->role_id, [1]))
        <a href="{{ $scheduleRoute }}" title="Gestionar Horarios" class="text-amber-600 hover:text-amber-900 bg-amber-100 hover:bg-amber-200 px-3 py-1 rounded transition shadow-sm font-semibold">
            ⏱ Turnos
        </a>
    @endif

    <a href="{{ $editRoute }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded transition shadow-sm font-semibold">Editar</a>
    
    <form action="{{ $destroyRoute }}" method="POST"
          onsubmit="event.preventDefault(); confirmForm(this, '{{ $deletable && $estado ? '¿Eliminar definitivamente este registro? Esta acción no se puede deshacer.' : ($estado ? '¿Desactivar este registro?' : '¿Activar este registro?') }}');"
          class="inline">
        @csrf
        @method('DELETE')
        @if($deletable && $estado)
            {{-- Se puede eliminar definitivamente --}}
            <button type="submit" class="text-red-700 hover:text-red-900 bg-red-200 hover:bg-red-300 px-3 py-1 rounded transition shadow-sm font-semibold">
                Eliminar
            </button>
        @elseif($estado)
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
@extends('layouts.app')

@section('title', 'Reservas')

@section('content')
    <x-page-header title="Gestión de Reservas" createRoute="{{ route('reservas.create') }}" buttonText="+ Nueva Reserva" />

    {{-- Tarjetas KPI (NEGRO PURO, ALTO CONTRASTE) --}}
    <p class="text-xs text-gray-400 mb-2 font-bold uppercase tracking-widest">Resumen del Mes (Clic para filtrar)</p>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <a href="{{ route('reservas.index') }}" class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition cursor-pointer {{ !$estadoFilter ? 'ring-2 ring-gray-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total (Mes)</h3>
            <p class="text-2xl font-extrabold text-[#D4AF37]">{{ $total }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'pendiente']) }}" class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition cursor-pointer {{ $estadoFilter == 'pendiente' ? 'ring-2 ring-yellow-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Pendientes</h3>
            <p class="text-2xl font-extrabold text-yellow-500">{{ $pendientes }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'confirmada']) }}" class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition cursor-pointer {{ $estadoFilter == 'confirmada' ? 'ring-2 ring-blue-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Confirmadas</h3>
            <p class="text-2xl font-extrabold text-blue-500">{{ $confirmadas }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'completada']) }}" class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition cursor-pointer {{ $estadoFilter == 'completada' ? 'ring-2 ring-green-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Completadas</h3>
            <p class="text-2xl font-extrabold text-green-500">{{ $completadas }}</p>
        </a>
        <a href="{{ route('reservas.index', ['estado' => 'cancelada']) }}" class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition cursor-pointer {{ $estadoFilter == 'cancelada' ? 'ring-2 ring-red-400' : '' }}">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Canceladas</h3>
            <p class="text-2xl font-extrabold text-red-500">{{ $canceladas }}</p>
        </a>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ session('success') }}</p></div> @endif
    @if($errors->any()) <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ $errors->first() }}</p></div> @endif

    {{-- Filtro de Búsqueda Avanzada (Guía 2) --}}
    <form method="GET" action="{{ route('reservas.index') }}" class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Buscar Cliente o Barbero</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ej. Juan Pérez..." class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Estado</label>
                <select name="estado" class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ request('estado') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="confirmada" {{ request('estado') == 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                    <option value="completada" {{ request('estado') == 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('estado') == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Desde</label>
                <input type="date" name="fecha_inicio" value="{{ request('fecha_inicio') }}" class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] text-gray-700">
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Hasta</label>
                <input type="date" name="fecha_fin" value="{{ request('fecha_fin') }}" class="w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37] text-gray-700">
            </div>
            <div class="md:col-span-5 flex flex-wrap justify-end gap-2 mt-2">
                <a href="{{ route('reservas.export-pdf', request()->query()) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded shadow transition uppercase tracking-wider text-xs flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Exportar PDF
                </a>
                <button type="submit" class="bg-black hover:bg-gray-900 text-[#D4AF37] font-bold py-2 px-6 rounded shadow transition uppercase tracking-wider text-xs">Filtrar</button>
                <a href="{{ route('reservas.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow transition uppercase tracking-wider text-xs flex items-center justify-center">Limpiar</a> 
            </div>
        </div>
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-black">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Fecha y Hora</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Cliente</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Empleado (Barbero)</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($reservations as $reserva)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }} <br>
                            <span class="text-xs text-gray-500 font-normal">{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 flex items-center gap-3">
                            @if($reserva->client->user && $reserva->client->user->avatar)
                                <img src="{{ $reserva->client->user->avatar_url }}" alt="Avatar" class="w-8 h-8 rounded-full object-cover border border-[#D4AF37]">
                            @else
                                <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs border border-gray-300">
                                    {{ substr($reserva->client->primer_nombre, 0, 1) }}
                                </div>
                            @endif
                            <span>{{ $reserva->client->primer_nombre }} {{ $reserva->client->primer_apellido }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $reserva->employee->user->primer_nombre }} {{ $reserva->employee->user->primer_apellido }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-semibold">${{ number_format($reserva->total, 0, ',', '.') }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($reserva->estado == 'pendiente') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">Pendiente</span>
                            @elseif($reserva->estado == 'confirmada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">Confirmada</span>
                            @elseif($reserva->estado == 'completada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-green-100 text-green-800 border border-green-200 shadow-sm">Completada</span>
                            @elseif($reserva->estado == 'cancelada') <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-red-100 text-red-800 border border-red-200 shadow-sm">Cancelada</span>
                            @else <span class="px-2 py-1 text-[10px] uppercase font-bold rounded-md bg-gray-200 text-gray-800 border border-gray-300 shadow-sm">No Asistió</span>
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium flex justify-end items-center space-x-2">
                            @php
                                $fecha = \Carbon\Carbon::parse($reserva->fecha)->format('Y-m-d');
                                $fechaHoraFin = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fecha . ' ' . $reserva->hora_fin);
                                $puedeCompletarse = now()->greaterThanOrEqualTo($fechaHoraFin);
                            @endphp 
                            
                            @if(in_array($reserva->estado, ['pendiente', 'confirmada']) && $puedeCompletarse)
                                <form action="{{ route('reservas.completar', $reserva) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" title="Marcar como Completada" class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition shadow-sm border border-green-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>
                            @endif
                            
                            @if(in_array($reserva->estado, ['pendiente', 'confirmada']))
                                <a href="{{ route('reservas.edit', $reserva) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 px-3 py-1 rounded shadow-sm font-semibold transition">Editar</a>
                            @endif
                            
                            @if(Auth::user()->role_id == 1)
                                <form action="{{ route('reservas.destroy', $reserva) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas ELIMINAR esta reserva permanentemente?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-3 py-1 rounded shadow-sm font-semibold transition">Eliminar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-6 text-center text-gray-500 italic">No hay reservas con este filtro.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($reservations->hasPages()) 
        <div class="mt-6 bg-black rounded-lg shadow-md p-4 border border-gray-800">
            {{ $reservations->links() }}
        </div> 
    @endif
@endsection
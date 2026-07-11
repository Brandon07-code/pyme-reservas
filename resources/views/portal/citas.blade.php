@extends('layouts.client')

@section('title', 'Mis Citas')

@section('content')
    <div class="max-w-5xl mx-auto">
        
        <div class="bg-black p-6 rounded-t-xl text-center border-b-4 border-[#D4AF37]">
            <h1 class="text-3xl font-extrabold text-white uppercase tracking-widest">Mi Historial de Citas</h1>
            <p class="text-[#D4AF37] font-semibold text-sm mt-1">Revisa tus reservas pasadas y futuras</p>
        </div>

        <div class="bg-white p-6 shadow-xl rounded-b-xl border border-gray-100">

            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
                    <p class="font-bold">{{ $errors->first() }}</p>
                </div>
            @endif

            @if($reservas->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 mb-4 text-lg">Aún no tienes citas registradas.</p>
                    <a href="{{ route('portal.agendar') }}" class="bg-[#D4AF37] hover:bg-yellow-500 text-black font-extrabold py-3 px-8 rounded-full shadow-lg transition uppercase tracking-wide">
                        🗓️ Agendar mi primera cita
                    </a>
                </div>
            @else
                <div class="space-y-6">
                @foreach($reservas as $reserva)
                        @php
                            // Corrección del bug de Carbon (Mismo fix que usamos en Edit)
                            $fechaFmt = \Carbon\Carbon::parse($reserva->fecha)->format('Y-m-d');
                            $fechaHoraInicio = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $reserva->hora_inicio);
                            $esFutura = now()->lessThan($fechaHoraInicio);
                        @endphp

                        <div class="border {{ $esFutura && in_array($reserva->estado, ['pendiente', 'confirmada']) ? 'border-[#D4AF37] shadow-md' : 'border-gray-200' }} rounded-lg overflow-hidden flex flex-col md:flex-row shadow-sm hover:shadow-md transition">    
                            {{-- BLOQUE FECHA (Izquierda) --}}
                            <div class="bg-gray-50 p-6 md:w-1/3 flex flex-col justify-center items-center border-b md:border-b-0 md:border-r border-gray-200">
                                <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($reserva->fecha)->translatedFormat('l') }}</span>
                                <span class="text-3xl font-extrabold text-black">{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</span>
                                <span class="text-md font-semibold text-[#D4AF37] mt-2">
                                    {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}
                                </span>
                            </div>

                            {{-- BLOQUE DETALLES (Centro) --}}
                            <div class="p-6 md:w-2/3 flex flex-col justify-between bg-white">
                                <div>
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-xl font-bold text-gray-900">Barbero: {{ $reserva->employee->user->primer_nombre }}</h3>
                                        
                                        {{-- Badge de Estado --}}
                                        @if($reserva->estado == 'pendiente') <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-md bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">Pendiente</span>
                                        @elseif($reserva->estado == 'confirmada') <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-md bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">Confirmada</span>
                                        @elseif($reserva->estado == 'completada') <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-md bg-green-100 text-green-800 border border-green-200 shadow-sm">Completada</span>
                                        @elseif($reserva->estado == 'cancelada') <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-md bg-red-100 text-red-800 border border-red-200 shadow-sm">Cancelada</span>
                                        @else <span class="px-3 py-1 text-[10px] uppercase font-bold rounded-md bg-gray-200 text-gray-800 border border-gray-300 shadow-sm">No Asistió</span>
                                        @endif
                                    </div>
                                    
                                    {{-- Lista de Servicios --}}
                                    <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1 mt-4">Servicios Seleccionados:</p>
                                    <ul class="text-sm text-gray-600 space-y-1">
                                        @foreach($reserva->services as $servicio)
                                            <li>• {{ $servicio->nombre }}</li>
                                        @endforeach
                                    </ul>
                                </div>

                                {{-- Precio y Botón Cancelar (Abajo) --}}
                                <div class="mt-6 pt-4 border-t border-gray-100 flex justify-between items-center">
                                    <span class="text-xl font-extrabold text-black">Total: ${{ number_format($reserva->total, 0, ',', '.') }}</span>
                                    
                                    @if(in_array($reserva->estado, ['pendiente', 'confirmada']) && $esFutura)
                                        <form action="{{ route('portal.citas.cancelar', $reserva) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta cita? Esta acción no se puede deshacer.');">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-red-600 hover:text-white border border-red-600 hover:bg-red-600 font-bold py-2 px-4 rounded transition text-sm">
                                                Cancelar Cita
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{-- Paginación --}}
                @if($reservas->hasPages())
                    <div class="mt-8 border-t border-gray-200 pt-6">
                        {{ $reservas->links() }}
                    </div>
                @endif
            @endif

        </div>
    </div>
@endsection
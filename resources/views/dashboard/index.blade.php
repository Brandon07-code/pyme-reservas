@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Resumen General</h1>

    @if(session('success')) 
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ session('success') }}</p></div> 
    @endif
    @if($errors->any()) 
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ $errors->first() }}</p></div> 
    @endif

    {{-- FILA 1: TARJETAS KPI (DISEÑO CLEAN PREMIUM) --}}
    <div class="grid grid-cols-2 {{ $esAdmin ? 'md:grid-cols-4' : 'md:grid-cols-3' }} gap-4 mb-8">
        
        <div class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Citas Restantes Hoy</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $citasHoy }}</p>
        </div>

        <div class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Agenda Mañana</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $citasManana }} Reservas</p>
            
        </div>

        @if($esAdmin)
            <div class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition">
                <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Ingresos (Mes)</h3>
                <p class="text-2xl font-extrabold text-[#D4AF37]">${{ number_format($ingresosMes, 0, ',', '.') }}</p>
            </div>

            <div class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition">
                <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Nuevos Clientes</h3>
                <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $clientesNuevos }}</p>
            </div>
        @else
            {{-- KPI EXCLUSIVO PARA EL BARBERO --}}
            <div class="bg-black rounded-lg shadow-lg p-5 hover:bg-gray-900 transition">
                <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-2">Mis Servicios (Mes)</h3>
                <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $misCortesMes }}</p>
            </div>
        @endif
    </div>

    {{-- FILA 2: GRÁFICA Y AGENDA DE HOY --}}
    <div class="grid grid-cols-1 {{ $esAdmin ? 'lg:grid-cols-3' : 'lg:grid-cols-1 max-w-4xl mx-auto' }} gap-6 mb-8">
        
        @if($esAdmin)
            <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-2 border border-gray-100">
                <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Evolución de Ingresos (Últimos 6 meses)</h2>
                <div class="relative h-72 w-full">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        @endif

        {{-- AGENDA CON ACCIÓN RÁPIDA INTEGRADA --}}
        <div class="bg-white shadow-md rounded-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4 flex items-center justify-between">
                Próximas Citas Hoy
                @if(!$esAdmin) <span class="text-xs bg-[#D4AF37] text-black px-2 py-1 rounded-full font-bold">Centro de Acción</span> @endif
            </h2>
            
            @if($proximasCitas->isEmpty())
                <p class="text-gray-500 text-sm text-center py-8 italic">Tu silla está libre. No hay más citas pendientes para hoy.</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach($proximasCitas as $cita)
                        @php
                            $fechaFmt = \Carbon\Carbon::parse($cita->fecha)->format('Y-m-d');
                            $fechaHoraFin = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $fechaFmt . ' ' . $cita->hora_fin);
                            $puedeCompletarse = now()->greaterThanOrEqualTo($fechaHoraFin);
                        @endphp 
                        
                        <li class="py-4 flex justify-between items-center group">
                            <div>
                                <p class="font-extrabold text-gray-900 text-sm">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                                <p class="text-xs text-gray-500 font-semibold">{{ $cita->client->primer_nombre }} {{ $cita->client->primer_apellido }}</p>
                            </div>
                            <div class="text-right flex items-center gap-3">
                                
                                {{-- BOTÓN COMPLETAR RÁPIDO (Directo en el Dashboard) --}}
                                @if(in_array($cita->estado, ['pendiente', 'confirmada']) && $puedeCompletarse)
                                    <form action="{{ route('reservas.completar', $cita) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" title="Marcar como Completada" class="flex items-center justify-center w-8 h-8 rounded-full bg-green-100 text-green-600 hover:bg-green-600 hover:text-white transition shadow-sm border border-green-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </form>
                                @endif

                                <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-full {{ $cita->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $cita->estado }}
                                </span>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-6 text-center">
                    <a href="{{ route('reservas.index') }}" class="inline-block bg-black hover:bg-gray-800 text-[#D4AF37] font-extrabold py-2 px-6 rounded-full shadow transition uppercase tracking-wider text-xs">
                        Ver Agenda Completa
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($esAdmin)
        {{-- FILA 3: ANALÍTICAS Y ALERTAS (SOLO ADMIN) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Alertas de Inventario</h2>
                @if($inventarioCritico->isEmpty())
                    <p class="text-green-600 text-sm font-semibold text-center py-4">✅ Inventario estable</p>
                @else
                    <ul class="divide-y divide-gray-100">
                        @foreach($inventarioCritico as $producto)
                            <li class="py-3 flex justify-between items-center">
                                <span class="text-sm text-gray-800 truncate">{{ $producto->nombre }}</span>
                                <span class="bg-red-600 text-white font-bold px-2 py-1 rounded-full text-[10px] uppercase tracking-wider">
                                    Quedan: {{ $producto->stock_actual }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Top Barberos (Mes)</h2>
                <ul class="divide-y divide-gray-100">
                    @foreach($topBarberos as $index => $barbero)
                        @if($barbero->reservations_count > 0)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="font-extrabold text-[#D4AF37] mr-3 text-lg">#{{ $index + 1 }}</span>
                                    <span class="text-sm text-gray-800 font-bold uppercase tracking-wider">{{ $barbero->user->primer_nombre }}</span>
                                </div>
                                <span class="text-xs text-gray-500 font-semibold">{{ $barbero->reservations_count }} cortes</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
                <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Servicios Populares</h2>
                <ul class="divide-y divide-gray-100">
                    @foreach($topServicios as $index => $servicio)
                        @if($servicio->reservations_count > 0)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center overflow-hidden">
                                    <span class="font-extrabold text-[#D4AF37] mr-3 text-lg">#{{ $index + 1 }}</span>
                                    <span class="text-sm text-gray-800 font-bold uppercase tracking-wider truncate w-32" title="{{ $servicio->nombre }}">{{ $servicio->nombre }}</span>
                                </div>
                                <span class="text-xs text-gray-500 font-semibold">{{ $servicio->reservations_count }} veces</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- SCRIPT GRÁFICA ADMIN --}}
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const ctx = document.getElementById('revenueChart');
                if (!ctx) return;
                const labels = {{ Illuminate\Support\Js::from($labelsGrafica) }};
                const data = {{ Illuminate\Support\Js::from($datosGrafica) }};
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Ingresos Mensuales (COP)',
                            data: data,
                            backgroundColor: '#D4AF37', // Dorado JyM
                            borderColor: '#000000',
                            borderWidth: 1,
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, ticks: { callback(value) { return '$' + value.toLocaleString('es-CO'); } } } },
                        plugins: { legend: { display: false } }
                    }
                });
            });
        </script>
    @endif
@endsection
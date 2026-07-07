@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Resumen General</h1>

   {{-- FILA 1: TARJETAS KPI (Ampliadas) --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
        
        {{-- KPI: Citas Pendientes Hoy --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Citas Restantes Hoy</h3>
            <p class="text-2xl font-bold text-gray-800">{{ $citasHoy }}</p>
        </div>

        {{-- KPI: Completadas Hoy --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Completadas Hoy</h3>
            <p class="text-2xl font-bold text-green-600">{{ $citasCompletadasHoy }}</p>
        </div>

        {{-- KPI: Canceladas Hoy --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Canceladas Hoy</h3>
            <p class="text-2xl font-bold text-red-600">{{ $citasCanceladasHoy }}</p>
        </div>

        {{-- KPI: Citas Mañana --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Agenda Mañana</h3>
            <p class="text-2xl font-bold text-yellow-600">{{ $citasManana }}</p>
        </div>

        {{-- KPI: Ingresos Mes --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-emerald-600">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Ingresos (Mes)</h3>
            <p class="text-xl font-bold text-emerald-600">${{ number_format($ingresosMes, 0, ',', '.') }}</p>
        </div>

        {{-- KPI: Nuevos Clientes --}}
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <h3 class="text-gray-500 text-[10px] font-bold uppercase tracking-wider mb-1">Nuevos Clientes (Mes)</h3>
            <p class="text-2xl font-bold text-purple-600">{{ $clientesNuevos }}</p>
        </div>
    </div>

    {{-- FILA 2: GRÁFICA Y AGENDA DE HOY --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Gráfica de Ingresos (Ocupa 2/3 del ancho) --}}
        <div class="bg-white shadow-md rounded-lg p-6 lg:col-span-2">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Evolución de Ingresos (Últimos 6 meses)</h2>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        {{-- Próximas Citas (Ocupa 1/3 del ancho) --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Agenda de Hoy</h2>
            @if($proximasCitas->isEmpty())
                <p class="text-gray-500 text-sm text-center py-4">No hay citas programadas para hoy.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($proximasCitas as $cita)
                        <li class="py-3 flex justify-between items-center">
                            <div>
                                <p class="font-bold text-gray-800 text-sm">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('h:i A') }}</p>
                                <p class="text-xs text-gray-500 truncate w-32" title="{{ $cita->client->primer_nombre }} {{ $cita->client->primer_apellido }}">
                                    {{ $cita->client->primer_nombre }} {{ $cita->client->primer_apellido }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold px-2 py-1 rounded-full {{ $cita->estado == 'pendiente' ? 'bg-yellow-100 text-yellow-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $cita->estado }}
                                </span>
                                <p class="text-xs text-indigo-600 mt-1">{{ $cita->employee->user->primer_nombre }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                <div class="mt-4 text-center">
                    <a href="{{ route('reservas.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-semibold transition">Ver agenda completa &rarr;</a>
                </div>
            @endif
        </div>
    </div>

    {{-- FILA 3: ANALÍTICAS Y ALERTAS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Alerta Inventario --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Alertas de Inventario</h2>
            @if($inventarioCritico->isEmpty())
                <p class="text-green-600 text-sm font-semibold text-center py-4">✅ Inventario estable</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($inventarioCritico as $producto)
                        <li class="py-3 flex justify-between items-center">
                            <span class="text-sm text-gray-800 truncate">{{ $producto->nombre }}</span>
                            <span class="bg-red-100 text-red-800 font-bold px-2 py-1 rounded-full text-xs">
                                {{ $producto->stock_actual }} left
                            </span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Top Barberos --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Top Barberos (Mes)</h2>
            @if($topBarberos->isEmpty() || $topBarberos->first()->reservations_count == 0)
                <p class="text-gray-500 text-sm text-center py-4">Aún no hay servicios completados.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($topBarberos as $index => $barbero)
                        @if($barbero->reservations_count > 0)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center">
                                    <span class="font-bold text-gray-400 mr-3">#{{ $index + 1 }}</span>
                                    <span class="text-sm text-gray-800 font-semibold">{{ $barbero->user->primer_nombre }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $barbero->reservations_count }} servicios</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>

        {{-- Top Servicios --}}
        <div class="bg-white shadow-md rounded-lg p-6">
            <h2 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Servicios Populares</h2>
            @if($topServicios->isEmpty() || $topServicios->first()->reservations_count == 0)
                <p class="text-gray-500 text-sm text-center py-4">No hay datos suficientes.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach($topServicios as $index => $servicio)
                        @if($servicio->reservations_count > 0)
                            <li class="py-3 flex justify-between items-center">
                                <div class="flex items-center overflow-hidden">
                                    <span class="font-bold text-gray-400 mr-3">#{{ $index + 1 }}</span>
                                    <span class="text-sm text-gray-800 truncate w-32" title="{{ $servicio->nombre }}">{{ $servicio->nombre }}</span>
                                </div>
                                <span class="text-xs text-gray-500">{{ $servicio->reservations_count }} veces</span>
                            </li>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>

    </div>

    {{-- SCRIPT PARA LA GRÁFICA DE CHART.JS --}}
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
                backgroundColor: 'rgba(59,130,246,.7)',
                borderColor: 'rgba(37,99,235,1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback(value) {
                            return '$' + value.toLocaleString('es-CO');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label(context) {
                            return '$' + context.raw.toLocaleString('es-CO');
                        }
                    }
                }
            }
        }
    });

});
</script>
@endsection
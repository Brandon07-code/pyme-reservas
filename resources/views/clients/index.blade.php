@extends('layouts.app')
@section('title', 'Clientes')
@section('content')
    <x-page-header title="Gestión de Clientes" createRoute="{{ route('clientes.create') }}" buttonText="+ Nuevo Cliente" />

    {{-- Tarjetas KPI JyM Style (Descansado) --}}
    <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-widest">Métricas de Clientes</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Clientes</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $totalClients }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Clientes Activos</h3>
            <p class="text-3xl font-extrabold text-green-500">{{ $activeClients }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Clientes Bloqueados</h3>
            <p class="text-3xl font-extrabold text-red-500">{{ $inactiveClients }}</p>
        </div>
    </div>

    @if($nuevosMes)
        <div class="mb-4 bg-[#D4AF37]/10 border border-[#D4AF37] text-[#92711A] px-4 py-3 rounded-lg flex items-center justify-between">
            <span class="text-sm font-bold">Mostrando nuevos clientes del mes actual</span>
            <a href="{{ route('clientes.index') }}" class="text-xs font-bold underline hover:text-black transition">Ver todos</a>
        </div>
    @endif

    <form method="GET" action="{{ route('clientes.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre, teléfono o email..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
        <button type="submit" class="bg-[#0f172a] hover:bg-black text-[#D4AF37] font-bold py-2 px-6 rounded shadow uppercase tracking-wider text-xs transition">Buscar</button>
        @if($search || $nuevosMes) <a href="{{ route('clientes.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow text-xs uppercase tracking-wider transition">Limpiar</a> @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-hidden border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0f172a]">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($clients as $cliente)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $cliente->primer_nombre }} {{ $cliente->primer_apellido }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cliente->telefono }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $cliente->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :estado="$cliente->estado" /></td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <x-action-buttons editRoute="{{ route('clientes.edit', $cliente) }}" destroyRoute="{{ route('clientes.destroy', $cliente) }}" :estado="$cliente->estado" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-gray-500 italic">No se encontraron clientes.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clients->hasPages())
        <div class="mt-6 bg-[#0f172a] rounded-lg shadow-md p-4 border border-gray-800">
        {{ $clients->appends(['search' => $search, 'nuevos_mes' => $nuevosMes ?: null])->links() }}
        </div>
    @endif
@endsection
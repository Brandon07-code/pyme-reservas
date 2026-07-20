@extends('layouts.app')
@section('title', 'Empleados')
@section('content')
    <x-page-header title="Gestión de Empleados" createRoute="{{ route('empleados.create') }}" buttonText="+ Nuevo Empleado" />

    {{-- Tarjetas KPI JyM Style (Descansado) --}}
    <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-widest">Resumen Operativo</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Empleados</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $total }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Personal Activo</h3>
            <p class="text-3xl font-extrabold text-green-500">{{ $activos }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Personal Inactivo</h3>
            <p class="text-3xl font-extrabold text-red-500">{{ $inactivos }}</p>
        </div>
    </div>

    @if(session('success')) <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ session('success') }}</p></div> @endif

    <form method="GET" action="{{ route('empleados.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o especialidad..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
        <button type="submit" class="bg-[#0f172a] hover:bg-black text-[#D4AF37] font-bold py-2 px-6 rounded shadow uppercase tracking-wider text-xs transition">Buscar</button>
        @if($search) <a href="{{ route('empleados.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow text-xs uppercase tracking-wider transition">Limpiar</a> @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0f172a]">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Especialidad</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Teléfono</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($employees as $empleado)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">{{ $empleado->user->primer_nombre }} {{ $empleado->user->primer_apellido }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empleado->especialidad ?? 'No definida' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empleado->telefono ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"><x-status-badge :estado="$empleado->estado" /></td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <x-action-buttons editRoute="{{ route('empleados.edit', $empleado) }}" destroyRoute="{{ route('empleados.destroy', $empleado) }}" :estado="$empleado->estado" scheduleRoute="{{ route('empleados.horarios.edit', $empleado) }}" />
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-gray-500 italic">No se encontraron empleados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($employees->hasPages())
        <div class="mt-6 bg-[#0f172a] rounded-lg shadow-md p-4 border border-gray-800">
            {{ $employees->appends(['search' => $search])->links() }}
        </div>
    @endif
@endsection
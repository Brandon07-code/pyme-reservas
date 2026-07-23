@extends('layouts.app')

@section('title', 'Usuarios')

@section('content')
    <x-page-header title="Gestión de Usuarios" createRoute="" buttonText="" />

    {{-- Tarjetas KPI JyM Style --}}
    <p class="text-[10px] text-gray-500 mb-2 font-bold uppercase tracking-widest">Resumen de Cuentas</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5 ">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Total Usuarios</h3>
            <p class="text-3xl font-extrabold text-[#D4AF37]">{{ $totalUsers }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5 ">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Usuarios Activos</h3>
            <p class="text-3xl font-extrabold text-green-500">{{ $activeUsers }}</p>
        </div>
        <div class="bg-[#0f172a] rounded-lg shadow-lg p-5 ">
            <h3 class="text-gray-400 text-[10px] font-bold uppercase tracking-widest mb-1">Usuarios Inactivos</h3>
            <p class="text-3xl font-extrabold text-red-500">{{ $inactiveUsers }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">{{ session('success') }}</p>
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-sm">
            <p class="font-bold">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Filtro de Búsqueda JyM Style --}}
    <form method="GET" action="{{ route('usuarios.index') }}" class="mb-6 flex gap-2">
        <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nombre o email..." class="w-full md:w-1/3 border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
        <button type="submit" class="bg-[#0f172a] hover:bg-black text-[#D4AF37] font-bold py-2 px-6 rounded shadow uppercase tracking-wider text-xs transition">Buscar</button>
        @if($search)
            <a href="{{ route('usuarios.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded shadow text-xs uppercase tracking-wider transition">Limpiar</a>
        @endif
    </form>

    <div class="bg-white shadow-md rounded-lg overflow-x-auto border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#0f172a]">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Nombre Completo</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Rol</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-[#D4AF37] uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ $user->primer_nombre }} {{ $user->primer_apellido }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            <span class="bg-gray-100 text-gray-800 py-1 px-3 rounded-md text-xs font-semibold">{{ $user->role->nombre }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm"><x-status-badge :estado="$user->estado" /></td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            
                            {{-- ANTI-SUICIDIO ADMIN: Si el usuario de la fila soy yo mismo, no muestro botón de desactivar --}}
                            @if(Auth::id() == $user->id)
                                <a href="{{ route('profile.edit') }}" class="text-[#D4AF37] hover:text-yellow-600 bg-gray-900 hover:bg-black px-4 py-2 rounded shadow-sm font-bold text-xs uppercase tracking-wider transition">Mi Perfil</a>
                            @else
                                <x-action-buttons editRoute="{{ route('usuarios.edit', $user) }}" destroyRoute="{{ route('usuarios.destroy', $user) }}" :estado="$user->estado" />
                            @endif
                            
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-6 py-6 text-center text-gray-500 italic">No se encontraron usuarios.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages()) 
        <div class="mt-6 bg-[#0f172a] rounded-lg shadow-md p-4 border border-gray-800">
            {{ $users->appends(['search' => $search])->links() }}
        </div> 
    @endif
@endsection
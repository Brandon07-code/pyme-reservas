@extends('layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Mi Perfil</h1>
        </div>

        @if(session('status') === 'profile-updated')
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
                <p>Datos personales actualizados correctamente.</p>
            </div>
        @endif

        @if(session('status') === 'password-updated')
            <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 rounded shadow-sm">
                <p>¡Tu contraseña ha sido cambiada con éxito!</p>
            </div>
        @endif

        {{-- TARJETA 1: Actualizar Información --}}
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Información Personal</h2>
            <form method="post" action="{{ route('profile.update') }}" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre</label>
                    <input type="text" name="primer_nombre" value="{{ old('primer_nombre', $user->primer_nombre) }}" required class="w-full border-gray-300 rounded-md border p-2">
                    @error('primer_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $user->segundo_nombre) }}" class="w-full border-gray-300 rounded-md border p-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido</label>
                    <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $user->primer_apellido) }}" required class="w-full border-gray-300 rounded-md border p-2">
                    @error('primer_apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-300 rounded-md border p-2">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-6 rounded shadow">Guardar Cambios</button>
                </div>
            </form>
        </div>

        {{-- TARJETA 2: Cambiar Contraseña --}}
        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Cambiar Contraseña</h2>
            <form method="post" action="{{ route('password.update') }}" class="grid grid-cols-1 gap-6 max-w-xl">
                @csrf
                @method('put')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual</label>
                    <input type="password" name="current_password" required class="w-full border-gray-300 rounded-md border p-2">
                    @error('current_password', 'updatePassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <input type="password" name="password" required class="w-full border-gray-300 rounded-md border p-2">
                    @error('password', 'updatePassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" required class="w-full border-gray-300 rounded-md border p-2">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">Actualizar Contraseña</button>
                </div>
            </form>
        </div>
    </div>
@endsection
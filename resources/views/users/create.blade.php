@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('content')
    <div class="max-w-4xl mx-auto">
        <x-page-header title="Registrar Nuevo Usuario" createRoute="" buttonText="" />
        <form action="{{ route('usuarios.store') }}" method="POST" class="bg-white shadow-md rounded-lg p-8">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div><label class="block text-sm font-medium mb-1">Primer Nombre *</label><input type="text" name="primer_nombre" value="{{ old('primer_nombre') }}" required class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Segundo Nombre</label><input type="text" name="segundo_nombre" value="{{ old('segundo_nombre') }}" class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Primer Apellido *</label><input type="text" name="primer_apellido" value="{{ old('primer_apellido') }}" required class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Segundo Apellido</label><input type="text" name="segundo_apellido" value="{{ old('segundo_apellido') }}" class="w-full border p-2 rounded"></div>
                
                <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" value="{{ old('email') }}" required class="w-full border p-2 rounded"> @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium mb-1">Contraseña *</label><input type="password" name="password" required class="w-full border p-2 rounded"></div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Rol *</label>
                    <select name="role_id" required class="w-full border p-2 rounded">
                        <option value="">Seleccione un rol</option>
                        @foreach($roles as $rol) <option value="{{ $rol->id }}">{{ $rol->nombre }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Estado *</label>
                    <select name="estado" required class="w-full border p-2 rounded">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t"><button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded">Guardar Usuario</button></div>
        </form>
    </div>
@endsection
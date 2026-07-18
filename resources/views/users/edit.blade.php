@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('content')
    <div class="max-w-4xl mx-auto">
        <x-page-header title="Editar Usuario" createRoute="" buttonText="" />
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-md rounded-lg p-8">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div><label class="block text-sm font-medium mb-1">Primer Nombre *</label><input type="text" name="primer_nombre" value="{{ old('primer_nombre', $usuario->primer_nombre) }}" required class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Segundo Nombre</label><input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $usuario->segundo_nombre) }}" class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Primer Apellido *</label><input type="text" name="primer_apellido" value="{{ old('primer_apellido', $usuario->primer_apellido) }}" required class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Segundo Apellido</label><input type="text" name="segundo_apellido" value="{{ old('segundo_apellido', $usuario->segundo_apellido) }}" class="w-full border p-2 rounded"></div>
                
                <div><label class="block text-sm font-medium mb-1">Email *</label><input type="email" name="email" value="{{ old('email', $usuario->email) }}" required class="w-full border p-2 rounded"> @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror</div>
                <div><label class="block text-sm font-medium mb-1">Contraseña <span class="text-gray-400 font-normal">(Dejar en blanco para no cambiarla)</span></label><input type="password" name="password" class="w-full border p-2 rounded"></div>

                <div><label class="block text-sm font-medium mb-1">Teléfono</label><input type="text" name="telefono" value="{{ old('telefono', $usuario->telefono) }}" class="w-full border p-2 rounded"></div>
                <div><label class="block text-sm font-medium mb-1">Dirección</label><input type="text" name="direccion" value="{{ old('direccion', $usuario->direccion) }}" class="w-full border p-2 rounded"></div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Avatar</label>
                    <div class="flex items-center gap-4">
                        @if($usuario->avatar)
                            <img src="{{ $usuario->avatar_url }}" alt="Avatar" class="w-16 h-16 rounded-full object-cover border-2 border-gray-300">
                        @else
                            <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold border border-gray-300">
                                {{ substr($usuario->primer_nombre, 0, 1) }}
                            </div>
                        @endif
                        <input type="file" name="avatar" accept="image/*" class="w-full border p-2 rounded">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium mb-1">Rol *</label>
                    <select name="role_id" required class="w-full border p-2 rounded">
                        @foreach($roles as $rol) <option value="{{ $rol->id }}" {{ $usuario->role_id == $rol->id ? 'selected' : '' }}>{{ $rol->nombre }}</option> @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Estado *</label>
                    <select name="estado" required class="w-full border p-2 rounded">
                        <option value="1" {{ $usuario->estado ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ !$usuario->estado ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end pt-4 border-t"><button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded">Actualizar Usuario</button></div>
        </form>
    </div>
@endsection
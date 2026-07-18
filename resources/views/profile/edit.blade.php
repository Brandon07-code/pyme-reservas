{{-- LÓGICA DINÁMICA DE LAYOUT: Si es cliente(3), usa client. Si es admin/empleado, usa app --}}
@extends(Auth::user()->role_id == 3 ? 'layouts.client' : 'layouts.app')

@section('title', 'Mi Perfil')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold {{ Auth::user()->role_id == 3 ? 'text-white' : 'text-gray-800' }}">Mi Perfil</h1>
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
        <div class="bg-white shadow-md rounded-lg p-8 border {{ Auth::user()->role_id == 3 ? 'border-[#D4AF37]/30' : 'border-gray-200' }}">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Información Personal</h2>
            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @csrf
                @method('patch')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre</label>
                    <input type="text" name="primer_nombre" value="{{ old('primer_nombre', $user->primer_nombre) }}" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('primer_nombre') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Segundo Nombre</label>
                    <input type="text" name="segundo_nombre" value="{{ old('segundo_nombre', $user->segundo_nombre) }}" class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido</label>
                    <input type="text" name="primer_apellido" value="{{ old('primer_apellido', $user->primer_apellido) }}" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('primer_apellido') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $user->telefono) }}" class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('telefono') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                    <textarea name="direccion" class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" rows="2">{{ old('direccion', $user->direccion) }}</textarea>
                    @error('direccion') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Avatar (Foto de Perfil)</label>
                    <input type="file" name="avatar" accept="image/*" class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @if($user->avatar)
                        <div class="mt-4">
                            <img src="{{ $user->avatar_url }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-2 border-[#D4AF37]">
                        </div>
                    @endif
                    @error('avatar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2 flex justify-end">
                    <button type="submit" class="{{ Auth::user()->role_id == 3 ? 'bg-[#D4AF37] hover:bg-yellow-500 text-black' : 'bg-gray-800 hover:bg-gray-900 text-white' }} font-bold py-2 px-6 rounded shadow transition">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>

        {{-- TARJETA 2: Cambiar Contraseña --}}
        <div class="bg-white shadow-md rounded-lg p-8 border {{ Auth::user()->role_id == 3 ? 'border-[#D4AF37]/30' : 'border-gray-200' }}">
            <h2 class="text-xl font-bold text-gray-800 border-b pb-2 mb-4">Cambiar Contraseña</h2>
            <form method="post" action="{{ route('password.update') }}" class="grid grid-cols-1 gap-6 max-w-xl">
                @csrf
                @method('put')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña Actual</label>
                    <input type="password" name="current_password" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('current_password', 'updatePassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nueva Contraseña</label>
                    <input type="password" name="password" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                    @error('password', 'updatePassword') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar Nueva Contraseña</label>
                    <input type="password" name="password_confirmation" required class="w-full border-gray-300 rounded-md border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="{{ Auth::user()->role_id == 3 ? 'bg-black hover:bg-gray-800 text-[#D4AF37]' : 'bg-blue-600 hover:bg-blue-700 text-white' }} font-bold py-2 px-6 rounded shadow transition">
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
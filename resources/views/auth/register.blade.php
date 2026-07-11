<x-guest-layout>
    <div class="text-center mb-8 border-b border-gray-200 pb-6">
        <div class="mx-auto w-16 h-16 bg-black rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-[#D4AF37]">
            <span class="text-2xl">💈</span>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-widest">Crear Cuenta</h2>
        <p class="text-xs text-[#D4AF37] font-bold uppercase tracking-widest mt-1">JyM Barbería & Perfumería</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="primer_nombre" class="block text-sm font-bold text-gray-700 mb-1">Primer Nombre *</label>
                <input id="primer_nombre" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="text" name="primer_nombre" :value="old('primer_nombre')" required autofocus />
            </div>

            <div>
                <label for="primer_apellido" class="block text-sm font-bold text-gray-700 mb-1">Primer Apellido *</label>
                <input id="primer_apellido" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="text" name="primer_apellido" :value="old('primer_apellido')" required />
            </div>
        </div>

        <div>
            <label for="telefono" class="block text-sm font-bold text-gray-700 mb-1">Teléfono Celular (Colombia) *</label>
            <input id="telefono" placeholder="Ej: 3001234567" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="text" name="telefono" :value="old('telefono')" required />
            @error('telefono') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico *</label>
            <input id="email" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="email" name="email" :value="old('email')" required />
            @error('email') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Contraseña *</label>
                <input id="password" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="password" name="password" required />
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">Confirmar Contraseña *</label>
                <input id="password_confirmation" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="password" name="password_confirmation" required />
            </div>
        </div>

        <div class="pt-6">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg text-sm font-bold text-[#D4AF37] bg-black hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition uppercase tracking-widest">
                Completar Registro
            </button>
        </div>

        <div class="text-center mt-4 border-t border-gray-100 pt-4">
            <a class="text-sm text-gray-500 hover:text-black font-bold transition" href="{{ route('login') }}">
                ¿Ya tienes una cuenta? Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>
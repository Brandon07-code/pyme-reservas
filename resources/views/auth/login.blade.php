<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-8 border-b border-gray-200 pb-6">
        <!-- Ícono estilizado -->
        <div class="mx-auto w-16 h-16 bg-black rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-[#D4AF37]">
            <span class="text-2xl">💈</span>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-widest">JyM Barbería</h2>
        <p class="text-xs text-[#D4AF37] font-bold uppercase tracking-widest mt-1">Acceso al Sistema</p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1">Correo Electrónico</label>
            <input id="email" class="block w-full border-gray-300 rounded-md shadow-sm border p-3 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs" />
        </div>

        <div>
            <label for="password" class="block text-sm font-bold text-gray-700 mb-1">Contraseña</label>
            <input id="password" class="block w-full border-gray-300 rounded-md shadow-sm border p-3 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-[#D4AF37] shadow-sm focus:ring-[#D4AF37]" name="remember">
                <span class="ms-2 text-sm text-gray-600 font-semibold">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-500 hover:text-black font-bold transition" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg text-sm font-bold text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition uppercase tracking-widest">
                Ingresar
            </button>
        </div>

        <div class="text-center mt-6 pt-4 border-t border-gray-100">
            <p class="text-sm text-gray-600">
                ¿Eres cliente y aún no tienes cuenta? <br>
                <a href="{{ route('register') }}" class="font-bold text-[#D4AF37] hover:text-yellow-600 transition block mt-2 uppercase text-xs tracking-wider">
                    Regístrate gratis aquí
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
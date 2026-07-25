<x-guest-layout>
    <x-auth-session-status class="mb-4 text-sm text-green-400 font-medium" :status="session('status')" />

    {{-- Logo + Título --}}
    <div class="text-center mb-2">
        <p class="text-5xl font-black mb-1" style="color:#D4AF37; letter-spacing:0.08em;">JyM</p>
        <h1 class="text-sm font-black text-white uppercase tracking-widest">Barbería & Perfumería</h1>
        <p class="text-xs mt-2 uppercase tracking-widest" style="color:#444; letter-spacing:0.18em;">Sistema de Acceso</p>
    </div>

    <div class="gold-divider"></div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Correo Electrónico</label>
            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="correo@ejemplo.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-xs" />
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="form-label">Contraseña</label>
            <input
                id="password"
                class="form-input"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-xs" />
        </div>

        {{-- Recordarme + Olvidé --}}
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="text-yellow-500 focus:ring-yellow-400 bg-gray-800" name="remember">
                <span class="ml-2 text-xs font-medium" style="color:#555">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-xs font-medium transition-colors" style="color:#444" onmouseover="this.style.color='#D4AF37'" onmouseout="this.style.color='#444'" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Botón --}}
        <div class="pt-2">
            <button type="submit" class="btn-submit">
                Ingresar al Sistema
            </button>
        </div>

        {{-- Registro --}}
        <div class="text-center pt-3">
            <div class="gold-divider"></div>
            <p class="text-xs mt-3" style="color:#444">¿Eres cliente y aún no tienes cuenta?</p>
            <a href="{{ route('register') }}"
               class="inline-block mt-1 text-xs font-bold uppercase tracking-widest transition-colors"
               style="color:#D4AF37"
               onmouseover="this.style.color='#fff'"
               onmouseout="this.style.color='#D4AF37'">
                Regístrate gratis aquí
            </a>
        </div>
    </form>
</x-guest-layout>
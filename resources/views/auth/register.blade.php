<x-guest-layout>
    {{-- Encabezado --}}
    <div class="text-center mb-2">
        <p class="text-4xl font-black mb-0" style="color:#D4AF37; letter-spacing:0.08em;">JyM</p>
        <h1 class="text-[11px] font-black text-white uppercase tracking-widest mt-1">Crear Cuenta</h1>
    </div>

    <div class="gold-divider"></div>

    <form method="POST" action="{{ route('register') }}" class="space-y-3">
        @csrf

        {{-- Nombres y Apellidos en dos columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="primer_nombre" class="form-label">Primer Nombre *</label>
                <input
                    id="primer_nombre"
                    class="form-input"
                    type="text"
                    name="primer_nombre"
                    value="{{ old('primer_nombre') }}"
                    required
                    autofocus
                    placeholder="Ej: Juan"
                />
                <x-input-error :messages="$errors->get('primer_nombre')" class="mt-1 text-red-400 text-xs" />
            </div>

            <div>
                <label for="primer_apellido" class="form-label">Primer Apellido *</label>
                <input
                    id="primer_apellido"
                    class="form-input"
                    type="text"
                    name="primer_apellido"
                    value="{{ old('primer_apellido') }}"
                    required
                    placeholder="Ej: Pérez"
                />
                <x-input-error :messages="$errors->get('primer_apellido')" class="mt-1 text-red-400 text-xs" />
            </div>
        </div>

        {{-- Teléfono --}}
        <div>
            <label for="telefono" class="form-label">Teléfono Celular *</label>
            <input
                id="telefono"
                class="form-input"
                type="text"
                name="telefono"
                value="{{ old('telefono') }}"
                required
                placeholder="Ej: 3001234567"
            />
            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-red-400 text-xs" />
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="form-label">Correo Electrónico *</label>
            <input
                id="email"
                class="form-input"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                placeholder="correo@ejemplo.com"
            />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-xs" />
        </div>

        {{-- Contraseña y Confirmación en dos columnas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="password" class="form-label">Contraseña *</label>
                <input
                    id="password"
                    class="form-input"
                    type="password"
                    name="password"
                    required
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-xs" />
            </div>

            <div>
                <label for="password_confirmation" class="form-label">Confirmar *</label>
                <input
                    id="password_confirmation"
                    class="form-input"
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="••••••••"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-red-400 text-xs" />
            </div>
        </div>

        {{-- Botón --}}
        <div class="pt-1">
            <button type="submit" class="btn-submit">
                Completar Registro
            </button>
        </div>

        {{-- Volver al login --}}
        <div class="text-center pt-1">
            <div class="gold-divider"></div>
            <p class="text-xs mt-2" style="color:#444">¿Ya tienes una cuenta registrada?</p>
            <a href="{{ route('login') }}"
               class="inline-block mt-1 text-xs font-bold uppercase tracking-widest transition-colors"
               style="color:#D4AF37"
               onmouseover="this.style.color='#fff'"
               onmouseout="this.style.color='#D4AF37'">
                Inicia sesión aquí
            </a>
        </div>
    </form>
</x-guest-layout>
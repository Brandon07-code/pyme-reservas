<x-guest-layout>
    <div class="text-center mb-8 border-b border-[#2a2a2a] pb-6">
        <!-- Ícono estilizado -->
        <div class="mx-auto w-16 h-16 bg-black rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-[#D4AF37]">
            <span class="text-2xl">🔐</span>
        </div>
        <h2 class="text-2xl font-extrabold text-white uppercase tracking-widest">Verificación</h2>
        <p class="text-xs text-[#D4AF37] font-bold uppercase tracking-widest mt-1">Paso de Seguridad Adicional</p>
    </div>

    @if (session('status'))
        <div class="mb-4 font-bold text-sm text-green-400 bg-green-900/30 border border-green-500/30 p-3 rounded text-center">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-gray-300 mb-6 text-center font-medium">
        Hemos enviado un código de 6 dígitos a tu correo electrónico. Por favor, ingrésalo a continuación para continuar.
        <br><br>
        <span class="text-xs text-gray-500">Modo Demostración: Abre Mailtrap (sandbox) para ver tu código.</span>
    </p>

    <form method="POST" action="{{ route('otp.verify.post') }}" class="space-y-6">
        @csrf

        <div>
            <label for="code" class="block text-sm font-bold text-gray-300 mb-1 text-center uppercase tracking-wider">Código OTP</label>
            <input id="code" class="block w-full text-center tracking-[1em] text-2xl font-black rounded-md shadow-sm border p-4 bg-[#1c1c1c] text-white border-[#2a2a2a] focus:ring-1 focus:ring-[#D4AF37] focus:border-[#D4AF37] outline-none" type="text" name="code" required autofocus maxlength="6" autocomplete="off" />
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-red-500 text-xs text-center" />
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-submit">
                Verificar Código
            </button>
        </div>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-[#2a2a2a]">
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="font-bold text-[#D4AF37] hover:text-white transition block mt-2 uppercase text-xs tracking-wider w-full text-center">
                ¿No recibiste el código? Reenviar
            </button>
        </form>
    </div>
</x-guest-layout>

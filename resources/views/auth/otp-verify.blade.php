<x-guest-layout>
    <div class="text-center mb-8 border-b border-gray-200 pb-6">
        <!-- Ícono estilizado -->
        <div class="mx-auto w-16 h-16 bg-black rounded-full flex items-center justify-center mb-4 shadow-lg border-2 border-[#D4AF37]">
            <span class="text-2xl">🔐</span>
        </div>
        <h2 class="text-2xl font-extrabold text-gray-900 uppercase tracking-widest">Verificación</h2>
        <p class="text-xs text-[#D4AF37] font-bold uppercase tracking-widest mt-1">Paso de Seguridad Adicional</p>
    </div>

    @if (session('status'))
        <div class="mb-4 font-bold text-sm text-green-600 bg-green-50 p-3 rounded text-center">
            {{ session('status') }}
        </div>
    @endif

    <p class="text-sm text-gray-600 mb-6 text-center font-medium">
        Hemos enviado un código de 6 dígitos a tu correo electrónico. Por favor, ingrésalo a continuación para continuar.
        <br><br>
        <span class="text-xs text-gray-400">Modo Demostración: Abre Mailtrap (sandbox) para ver tu código.</span>
    </p>

    <form method="POST" action="{{ route('otp.verify.post') }}" class="space-y-6">
        @csrf

        <div>
            <label for="code" class="block text-sm font-bold text-gray-700 mb-1 text-center">Código OTP</label>
            <input id="code" class="block w-full text-center tracking-[1em] text-2xl font-black border-gray-300 rounded-md shadow-sm border p-4 focus:ring-[#D4AF37] focus:border-[#D4AF37]" type="text" name="code" required autofocus maxlength="6" autocomplete="off" />
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-red-500 text-xs text-center" />
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-lg text-sm font-bold text-white bg-black hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black transition uppercase tracking-widest">
                Verificar Código
            </button>
        </div>
    </form>

    <div class="text-center mt-6 pt-4 border-t border-gray-100">
        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="font-bold text-[#D4AF37] hover:text-yellow-600 transition block mt-2 uppercase text-xs tracking-wider w-full text-center">
                ¿No recibiste el código? Reenviar
            </button>
        </form>
    </div>
</x-guest-layout>

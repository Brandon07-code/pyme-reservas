<x-guest-layout>
    <div class="text-center mb-8 border-b pb-4">
        <h2 class="text-xl font-bold text-gray-800">Crea tu cuenta</h2>
        <p class="text-sm text-gray-500 mt-1">Agenda tus citas y consulta nuestro catálogo</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="primer_nombre" class="block text-sm font-medium text-gray-700 mb-1">Primer Nombre *</label>
                <input id="primer_nombre" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="primer_nombre" :value="old('primer_nombre')" required autofocus />
                <x-input-error :messages="$errors->get('primer_nombre')" class="mt-1 text-red-500 text-xs" />
            </div>

            <div>
                <label for="primer_apellido" class="block text-sm font-medium text-gray-700 mb-1">Primer Apellido *</label>
                <input id="primer_apellido" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="primer_apellido" :value="old('primer_apellido')" required />
                <x-input-error :messages="$errors->get('primer_apellido')" class="mt-1 text-red-500 text-xs" />
            </div>
        </div>

        <div>
            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono Celular *</label>
            <input id="telefono" placeholder="Ej: 3001234567" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="telefono" :value="old('telefono')" required />
            <x-input-error :messages="$errors->get('telefono')" class="mt-1 text-red-500 text-xs" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico *</label>
            <input id="email" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="email" name="email" :value="old('email')" required />
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña *</label>
            <input id="password" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Contraseña *</label>
            <input id="password_confirmation" class="block w-full border-gray-300 rounded-md shadow-sm border p-2 focus:ring-indigo-500 focus:border-indigo-500" type="password" name="password_confirmation" required />
        </div>

        <div class="pt-4">
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                Completar Registro
            </button>
        </div>

        <div class="text-center mt-4 border-t border-gray-100 pt-4">
            <a class="text-sm text-indigo-600 hover:text-indigo-900 font-semibold transition" href="{{ route('login') }}">
                ¿Ya tienes una cuenta? Inicia sesión
            </a>
        </div>
    </form>
</x-guest-layout>
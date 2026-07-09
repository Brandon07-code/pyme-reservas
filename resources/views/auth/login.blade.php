<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso al Sistema - PYME Reservas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased text-gray-900 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md bg-white rounded-xl shadow-lg p-8 m-4">
        
        <!-- Logo / Titulo -->
        <div class="text-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight">💈 PYME Reservas</h1>
            <p class="text-sm text-gray-500 mt-2">Acceso Administrativo y Operativo</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-green-600 font-bold text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-5">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Correo Electrónico</label>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-500 text-xs italic" />
            </div>

            <!-- Password -->
            <div class="mb-5">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Contraseña</label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition-colors">
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs italic" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-6">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                    <span class="ms-2 text-sm text-gray-600">Recordarme</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm text-blue-600 hover:text-blue-800 underline transition" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <!-- Botón Ingresar -->
            <div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200">
                    Ingresar al Sistema
                </button>
            </div>
        </form>

        <div class="mt-8 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} PYME Reservas. Cartago, Valle.
        </div>
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JYM Barbería y Perfumería - @yield('title', 'Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-indigo-900 p-4 w-full shadow-lg">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex justify-center md:justify-start font-extrabold text-white">
                <a class="text-white no-underline hover:text-indigo-200 hover:no-underline" href="{{ route('portal.index') }}">
                    <span class="text-xl pl-2">💈 JYM Barbería</span>
                </a>
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm">
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('portal.index') ? 'text-white font-bold border-b-2 border-white' : 'text-indigo-300 no-underline hover:text-indigo-100 hover:no-underline' }}" href="{{ route('portal.index') }}">Catálogo</a>
                    </li>
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 text-indigo-300 no-underline hover:text-indigo-100 hover:no-underline" href="#">Mis Citas</a>
                    </li>
                    
                    @auth
                        <li class="ml-4 pl-4 border-l border-indigo-700 flex items-center space-x-4">
                            <span class="text-indigo-200 font-semibold">Hola, {{ Auth::user()->primer_nombre }}</span>
                            
                            <a href="{{ route('profile.edit') }}" class="text-indigo-300 hover:text-white transition">Mi Perfil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded shadow text-xs transition">Salir</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4 mb-12">
        @yield('content')
    </div>

    <footer class="bg-indigo-900 text-center text-indigo-300 py-6 text-sm border-t border-indigo-800">
        &copy; {{ date('Y') }} JYM Barbería y Perfumería. Cartago, Valle del Cauca.
    </footer>

</body>
</html>
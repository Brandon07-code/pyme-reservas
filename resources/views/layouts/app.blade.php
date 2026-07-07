<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PYME Reservas - @yield('title', 'Inicio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-gray-900 p-4 w-full shadow">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex justify-center md:justify-start font-extrabold text-white">
                <a class="text-white no-underline hover:text-gray-300 hover:no-underline" href="/">
                    <span class="text-xl pl-2">💈 PYME Reservas</span>
                </a>
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm">
                    
                    {{-- Usamos request()->routeIs() para saber en qué pestaña estamos y aplicar clases dinámicas --}}
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('dashboard') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    
                    @if(Auth::check() && Auth::user()->role_id == 1)
                        <li class="mr-3">
                            <a class="inline-block py-2 px-2 {{ request()->routeIs('usuarios.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('usuarios.index') }}">Usuarios</a>
                        </li>
                        <li class="mr-3">
                            <a class="inline-block py-2 px-2 {{ request()->routeIs('empleados.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('empleados.index') }}">Empleados</a>
                        </li>
                        <li class="mr-3">
                            <a class="inline-block py-2 px-2 {{ request()->routeIs('clientes.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('clientes.index') }}">Clientes</a>
                        </li>
                        <li class="mr-3">
                            <a class="inline-block py-2 px-2 {{ request()->routeIs('servicios.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('servicios.index') }}">Servicios</a>
                        </li>
                        <li class="mr-3">
                            <a class="inline-block py-2 px-2 {{ request()->routeIs('productos.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('productos.index') }}">Productos</a>
                        </li>
                    @endif
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('reservas.*') ? 'text-white font-bold border-b-2 border-white' : 'text-gray-400 no-underline hover:text-gray-200 hover:no-underline' }}" href="{{ route('reservas.index') }}">Reservas</a>
                    </li>
                    
                    @auth
                        <li class="ml-4 pl-4 border-l border-gray-600 flex items-center">
                            <span class="text-gray-300 font-semibold mr-4">Hola, {{ Auth::user()->primer_nombre }}</span>
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

    <div class="container mx-auto mt-8 p-4">
        @yield('content')
    </div>

</body>
</html>
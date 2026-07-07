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
       <div class="flex justify-center md:justify-start">
    <a href="/" class="flex items-center gap-3 group">

        <div class="w-11 h-11 rounded-full bg-gradient-to-br from-blue-600 to-indigo-700 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
            <span class="text-xl">💈</span>
        </div>

        <div class="leading-tight">
            <h1 class="text-2xl font-black tracking-wide text-white">
                PYME
                <span class="text-blue-400">Reservas</span>
            </h1>
            <p class="text-[11px] text-gray-400 uppercase tracking-[0.25em]">
                Sistema de Gestion de Citas y Reservas
            </p>
        </div>

    </a>
</div>
            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
               <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm">
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/">Inicio</a></li>
                    
                    @if(Auth::user()->role_id == 1)
                        <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/usuarios">Usuarios</a></li>
                        <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/empleados">Empleados</a></li>
                        <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/clientes">Clientes</a></li>
                        <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/servicios">Servicios</a></li>
                        <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/productos">Productos</a></li>
                    @endif
                    
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/reservas">Reservas</a></li>
                    
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
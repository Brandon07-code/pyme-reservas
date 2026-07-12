<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PYME Reservas - @yield('title', 'Inicio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-gray-900 p-4 w-full shadow border-b border-gray-800">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex items-center justify-center md:justify-start font-extrabold text-white">
                <a class="text-white no-underline hover:text-gray-300 transition" href="/">
                    <span class="text-xl pl-2">💈 PYME Reservas</span>
                </a>
                
                {{-- NUEVO: RELOJ EN TIEMPO REAL --}}
                <div class="ml-6 pl-6 border-l border-gray-700 hidden md:block">
                    <span id="live-clock" class="text-xs font-semibold text-[#D4AF37] tracking-widest uppercase bg-black px-3 py-1 rounded-full border border-gray-700 shadow-inner">
                        Cargando reloj...
                    </span>
                </div>
            </div>

            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm">
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('dashboard') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('dashboard') }}">Inicio</a>
                    </li>
                    
                    @if(Auth::check() && Auth::user()->role_id == 1)
                        <li class="mr-3"><a class="inline-block py-2 px-2 {{ request()->routeIs('usuarios.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                        <li class="mr-3"><a class="inline-block py-2 px-2 {{ request()->routeIs('empleados.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('empleados.index') }}">Empleados</a></li>
                        <li class="mr-3"><a class="inline-block py-2 px-2 {{ request()->routeIs('clientes.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('clientes.index') }}">Clientes</a></li>
                        <li class="mr-3"><a class="inline-block py-2 px-2 {{ request()->routeIs('servicios.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('servicios.index') }}">Servicios</a></li>
                        <li class="mr-3"><a class="inline-block py-2 px-2 {{ request()->routeIs('productos.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('productos.index') }}">Productos</a></li>
                    @endif
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('reservas.*') ? 'text-white font-bold border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-white transition' }}" href="{{ route('reservas.index') }}">Reservas</a>
                    </li>
                    
                    @auth
                        <li class="ml-4 pl-4 border-l border-gray-700 flex items-center space-x-4">
                            <span class="text-gray-300 font-semibold">Hola, <span class="text-[#D4AF37]">{{ Auth::user()->primer_nombre }}</span></span>
                            
                            <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-white transition">Mi Perfil</a>

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

    {{-- SCRIPT PARA EL RELOJ EN TIEMPO REAL --}}
    <script>
        function updateClock() {
            const now = new Date();
           
            const options = { 
                timeZone: 'America/Bogota',
                weekday: 'long', 
                day: 'numeric', 
                month: 'short', 
                hour: '2-digit', 
                minute: '2-digit',
                second: '2-digit',
                hour12: true 
            };
            const timeString = now.toLocaleString('es-CO', options);
            document.getElementById('live-clock').textContent = timeString;
        }
        
        // Actualizar cada segundo
        setInterval(updateClock, 1000);
        updateClock(); // Llamada inicial
    </script>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JyM Barbería - @yield('title', 'Inicio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-gray-900 p-4 w-full shadow border-b border-gray-800">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            
            <div class="flex items-center justify-start w-full md:w-1/4 mb-4 md:mb-0">
                <a class="text-[#D4AF37] no-underline hover:text-yellow-300 transition flex items-center" href="/">
                    <span class="text-xl mr-2">💈</span> 
                    <span class="text-lg tracking-widest uppercase font-extrabold">JyM <span class="text-white font-light text-xs hidden lg:inline">Reservas</span></span>
                </a>
                <div class="ml-4 pl-4 border-l border-gray-700 hidden xl:block">
                    <span id="live-clock" class="text-[10px] font-bold text-[#D4AF37] tracking-widest uppercase bg-black px-2 py-1 rounded-full shadow-inner border border-gray-800">
                        --:--
                    </span>
                </div>
            </div>

            <div class="flex justify-center w-full md:w-2/4">
                <ul class="flex flex-wrap justify-center space-x-2 lg:space-x-6 items-center text-sm font-semibold">
                    <li><a class="inline-block py-2 px-2 {{ request()->routeIs('dashboard') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('dashboard') }}">Inicio</a></li>
                    
                    @if(Auth::check() && Auth::user()->role_id == 1)
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('usuarios.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('usuarios.index') }}">Usuarios</a></li>
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('empleados.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('empleados.index') }}">Empleados</a></li>
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('clientes.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('clientes.index') }}">Clientes</a></li>
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('servicios.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('servicios.index') }}">Servicios</a></li>
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('productos.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('productos.index') }}">Productos</a></li>
                        <li><a class="inline-block py-2 px-2 {{ request()->routeIs('orders.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('orders.index') }}">Pedidos</a></li>
                    @endif
                    
                    <li><a class="inline-block py-2 px-2 {{ request()->routeIs('reservas.*') ? 'text-white border-b-2 border-[#D4AF37]' : 'text-gray-400 hover:text-white transition' }}" href="{{ route('reservas.index') }}">Reservas</a></li>
                </ul>
            </div>

            <div class="flex items-center justify-end w-full md:w-1/4 mt-4 md:mt-0">
                @auth
                    <div class="flex items-center space-x-3">
                        
                        {{-- CAMPANITA DE NOTIFICACIONES --}}
                        <div class="relative mr-4">
                            <button class="text-gray-400 hover:text-[#D4AF37] transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                            {{-- Notificaciones Mockup (Por ahora) --}}
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[8px] font-bold leading-none text-black bg-[#D4AF37] rounded-full transform translate-x-1/2 -translate-y-1/2">
                                0
                            </span>
                        </div>

                        <span class="text-gray-400 text-xs uppercase tracking-wider hidden lg:inline">Hola, <span class="text-[#D4AF37] font-bold">{{ Auth::user()->primer_nombre }}</span></span>
                        
                        <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-white transition text-xs uppercase tracking-wider">Mi Perfil</a>

                        <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-[10px] uppercase tracking-wider transition shadow-sm">Salir</button>
                        </form>
                    </div>
                @endauth
            </div>

        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4 mb-12">
        @yield('content')
    </div>

    <script>
        function updateClock() {
            const now = new Date();
            const options = { timeZone: 'America/Bogota', weekday: 'short', day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const clockEl = document.getElementById('live-clock');
            if (clockEl) clockEl.textContent = now.toLocaleString('es-CO', options);
        }
        setInterval(updateClock, 1000);
        updateClock();
    </script>
</body>
</html>
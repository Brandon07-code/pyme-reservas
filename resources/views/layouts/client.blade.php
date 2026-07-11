<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JyM Barbería y Perfumería - @yield('title', 'Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-black p-4 w-full shadow-lg border-b border-[#D4AF37]/20">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex justify-center md:justify-start font-extrabold">
                <a class="text-[#D4AF37] no-underline hover:text-yellow-300 transition flex items-center" href="{{ route('portal.index') }}">
                    <span class="text-2xl mr-2">💈</span> 
                    <span class="text-xl tracking-wider uppercase">JyM <span class="text-white font-light text-sm hidden sm:inline">Barbería & Perfumería</span></span>
                </a>
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm font-semibold">
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('portal.index') ? 'text-[#D4AF37] border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-[#D4AF37] transition' }}" href="{{ route('portal.index') }}">Catálogo</a>
                    </li>
                    
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('portal.citas') ? 'text-[#D4AF37] border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-[#D4AF37] transition' }}" href="{{ route('portal.citas') }}">Mis Citas</a>
                    </li>
                    
                    @auth
                        <li class="ml-4 pl-4 border-l border-gray-700 flex items-center space-x-4">
                            <span class="text-gray-300">Hola, <span class="text-[#D4AF37]">{{ Auth::user()->primer_nombre }}</span></span>
                            
                            <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-white transition">Mi Perfil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded shadow text-xs transition">Salir</button>
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

    <!-- Footer Negro y Dorado -->
    <footer class="bg-black text-center text-gray-400 py-8 text-sm border-t border-[#D4AF37]/30">
        <p class="font-bold text-[#D4AF37] text-lg tracking-wider mb-2">JyM BARBERÍA & PERFUMERÍA</p>
        <p class="mb-2">Atrae y seduce al instante</p>
        <p>📞 314 554 9069 | Cartago, Valle del Cauca</p>
        <p class="mt-4 text-xs opacity-50">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
    </footer>

</body>
</html>
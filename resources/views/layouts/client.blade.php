<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JyM Barbería y Perfumería - @yield('title', 'Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px) } to { opacity:1; transform:none } }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-enter { animation: slideInRight 0.3s ease-out forwards; }
    </style>
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

                    {{-- NUEVO: MIS PEDIDOS --}}
                    <li class="mr-3">
                        <a class="inline-block py-2 px-2 {{ request()->routeIs('portal.pedidos') ? 'text-[#D4AF37] border-b-2 border-[#D4AF37]' : 'text-gray-400 no-underline hover:text-[#D4AF37] transition' }}" href="{{ route('portal.pedidos') }}">Mis Pedidos</a>
                    </li>

                    @php $cartCount = count((array) session('cart')); @endphp
                    <li class="mr-3">
                        <a href="{{ route('portal.cart.index') }}" class="relative inline-block py-2 px-2 text-gray-400 hover:text-[#D4AF37] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            @if($cartCount > 0)
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-[10px] font-bold leading-none text-black bg-[#D4AF37] rounded-full transform translate-x-1/2 -translate-y-1/2 border border-black">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
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
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none">
        @if(session('success'))
            <div class="toast-message bg-green-600 text-white px-6 py-4 rounded-lg shadow-xl toast-enter flex items-center border-l-4 border-green-800">
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any() || session('error'))
            <div class="toast-message bg-red-600 text-white px-6 py-4 rounded-lg shadow-xl toast-enter flex items-center border-l-4 border-red-800">
                <span class="font-bold text-sm tracking-wide">{{ session('error') ?? $errors->first() }}</span>
            </div>
        @endif
    </div>

    <div class="container mx-auto mt-8 p-4 mb-12 animate-fade-in">
        @yield('content')
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.querySelectorAll('.toast-message').forEach(function(toast) {
                    toast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(100%)';
                    setTimeout(() => toast.remove(), 500);
                });
            }, 4000);
        });
    </script>

    <footer class="bg-black text-center text-gray-400 py-8 text-sm border-t border-[#D4AF37]/30">
        <p class="font-bold text-[#D4AF37] text-lg tracking-wider mb-2">JyM BARBERÍA & PERFUMERÍA</p>
        <p class="mb-2">Atrae y seduce al instante</p>
        <p>📞 314 554 9069 | Cartago, Valle del Cauca</p>
        <p class="mt-4 text-xs opacity-50">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
    </footer>

</body>
</html>
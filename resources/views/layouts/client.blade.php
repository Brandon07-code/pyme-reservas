<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JyM Barbería y Perfumería - @yield('title', 'Portal')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        @keyframes fadeIn { from { opacity:0; transform:translateY(10px) } to { opacity:1; transform:none } }
        .animate-fade-in { animation: fadeIn 0.4s ease-out forwards; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .toast-enter { animation: slideInRight 0.3s ease-out forwards; }
    </style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal text-gray-800">
    
   <nav class="bg-black p-4 w-full shadow-lg border-b border-[#D4AF37]/20" x-data="{ mobileMenuOpen: false }">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex items-center justify-between w-full md:w-1/4">
                <a class="text-[#D4AF37] no-underline hover:text-yellow-300 transition flex items-center" href="{{ route('portal.index') }}">
                    <span class="text-2xl mr-2">💈</span> 
                    <span class="text-xl tracking-wider uppercase font-extrabold">JyM <span class="text-white font-light text-sm hidden sm:inline">Barbería & Perfumería</span></span>
                </a>
                
                {{-- Botón Hamburguesa --}}
                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button" class="md:hidden text-gray-400 hover:text-[#D4AF37] focus:outline-none">
                    <svg class="h-8 w-8" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': mobileMenuOpen, 'inline-flex': !mobileMenuOpen }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !mobileMenuOpen, 'inline-flex': mobileMenuOpen }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            {{-- Contenedor colapsable --}}
            <div :class="mobileMenuOpen ? 'block' : 'hidden'" class="w-full md:flex md:items-center md:w-auto md:flex-1 md:justify-between transition-all duration-300 ease-in-out mt-4 md:mt-0">
                <ul class="flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-4 lg:space-x-6 items-center text-sm font-semibold w-full md:w-auto border-t md:border-t-0 border-[#D4AF37]/20 pt-4 md:pt-0">
                    
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
                        <li class="w-full md:w-auto flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4 md:border-l md:border-gray-700 md:pl-4 pt-4 md:pt-0 border-t md:border-t-0 border-[#D4AF37]/20">
                            
                            {{-- CAMPANITA DE NOTIFICACIONES --}}
                            @php 
                                $notificaciones = Auth::user()->unreadNotifications;
                                $notificacionesCount = $notificaciones->count(); 
                            @endphp
                            
                            <div class="relative mr-2" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="text-gray-400 hover:text-[#D4AF37] transition relative focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span id="notificaciones-badge" class="{{ $notificacionesCount > 0 ? '' : 'hidden' }} absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[10px] font-bold leading-none text-black bg-[#D4AF37] rounded-full transform translate-x-1/2 -translate-y-1/2 border border-black">
                                        {{ $notificacionesCount }}
                                    </span>
                                </button>

                                {{-- Dropdown de Notificaciones --}}
                                <div x-show="open" style="display: none;" class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-2xl overflow-hidden z-50 border border-gray-200">
                                    <div class="bg-gray-50 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                                        <h3 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Notificaciones</h3>
                                        <form action="{{ route('notificaciones.leer') }}" method="POST" id="form-marcar-leidas" class="{{ $notificacionesCount > 0 ? '' : 'hidden' }}">
                                            @csrf
                                            <button type="submit" class="text-[10px] text-blue-600 hover:underline">Marcar leídas</button>
                                        </form>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto" id="notificaciones-lista">
                                        @include('partials.notifications-list')
                                    </div>
                                </div>
                            </div>

                            <span class="text-gray-300">Hola, <span class="text-[#D4AF37] font-bold">{{ Auth::user()->primer_nombre }}</span></span>
                            
                            <a href="{{ route('profile.edit') }}" class="text-gray-400 hover:text-white transition">Mi Perfil</a>

                            <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-1 px-3 rounded shadow text-xs transition uppercase font-bold">Salir</button>
                            </form>
                        </li>
                    @endauth
                </ul>
            </div>
            {{-- Fin del contenedor colapsable --}}
        </div>
    </nav>
    <div id="toast-container" class="fixed top-5 right-5 z-50 flex flex-col gap-3 pointer-events-none">
        @if(session('success'))
            <div class="toast-message bg-green-600 text-white px-6 py-4 shadow-xl toast-enter flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span class="font-semibold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        @endif
        @if($errors->any() || session('error'))
            <div class="toast-message bg-red-600 text-white px-6 py-4 shadow-xl toast-enter flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                <span class="font-semibold text-sm tracking-wide">{{ session('error') ?? $errors->first() }}</span>
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

    {{-- Modal de confirmación personalizado (reemplaza el confirm() del navegador) --}}
    <div id="confirm-modal" class="fixed inset-0 z-[999] flex items-center justify-center hidden" style="background:rgba(0,0,0,0.7)">
        <div class="bg-[#1a1a1a] w-full max-w-sm mx-4 p-7 shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-red-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                </div>
                <h3 class="text-white font-bold text-base" id="confirm-title">Confirmar acción</h3>
            </div>
            <p class="text-gray-400 text-sm mb-7 leading-relaxed" id="confirm-message"></p>
            <div class="flex gap-3 justify-end">
                <button id="confirm-cancel" class="px-5 py-2 text-sm font-semibold text-gray-400 hover:text-white transition-colors">
                    Cancelar
                </button>
                <button id="confirm-ok" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-bold transition-colors">
                    Confirmar
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const confirmModal   = document.getElementById('confirm-modal');
            const confirmMessage = document.getElementById('confirm-message');
            const confirmOk      = document.getElementById('confirm-ok');
            const confirmCancel  = document.getElementById('confirm-cancel');
            let pendingForm      = null;

            window.confirmForm = function(form, message) {
                pendingForm = form;
                confirmMessage.textContent = message;
                confirmModal.classList.remove('hidden');
            };

            confirmOk.addEventListener('click', function () {
                confirmModal.classList.add('hidden');
                if (pendingForm) { pendingForm.submit(); pendingForm = null; }
            });

            confirmCancel.addEventListener('click', function () {
                confirmModal.classList.add('hidden');
                pendingForm = null;
            });

            confirmModal.addEventListener('click', function (e) {
                if (e.target === confirmModal) {
                    confirmModal.classList.add('hidden');
                    pendingForm = null;
                }
            });
        });
    </script>

    <script>
        @auth
        // Sondeo en tiempo real para notificaciones (AJAX Polling) para el cliente
        function checkNotifications() {
            fetch('{{ route('notificaciones.check') }}', {
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const badge = document.getElementById('notificaciones-badge');
                const btnMarcar = document.getElementById('form-marcar-leidas');
                const lista = document.getElementById('notificaciones-lista');
                
                if (badge && lista) {
                    if (data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                        if(btnMarcar) btnMarcar.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                        if(btnMarcar) btnMarcar.classList.add('hidden');
                    }
                    
                    lista.innerHTML = data.html;
                }
            })
            .catch(err => console.error('Error fetching notifications:', err));
        }
        
        // Revisar si hay nuevas notificaciones cada 15 segundos
        setInterval(checkNotifications, 15000);
        @endauth
    </script>

</body>
</html>
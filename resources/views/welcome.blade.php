<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JyM Barbería & Perfumería - Atrae y seduce al instante</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700;900&display=swap');
        body { font-family: 'Montserrat', sans-serif; }
        .bg-pattern {
            background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px), radial-gradient(#D4AF37 0.5px, #050505 0.5px);
            background-size: 20px 20px;
            background-position: 0 0, 10px 10px;
            opacity: 0.1;
        }
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out forwards;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }
    </style>
</head>
<body class="bg-[#050505] text-white min-h-screen flex flex-col justify-between overflow-hidden relative">
    
    <!-- Pattern Overlay -->
    <div class="absolute inset-0 bg-pattern z-0 pointer-events-none"></div>
    
    <!-- Gradient Glow -->
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-[#D4AF37] opacity-5 rounded-full blur-[120px] z-0 pointer-events-none"></div>

    <!-- Small Admin Login Link at the top -->
    <div class="absolute top-6 right-6 z-20 opacity-0 animate-fade-in-up">
        <a href="{{ route('login') }}" class="text-[10px] font-bold text-gray-500 hover:text-[#D4AF37] uppercase tracking-widest transition-colors flex items-center border border-gray-800 rounded-full px-3 py-1 bg-black/50 backdrop-blur-sm hover:border-[#D4AF37]/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
            </svg>
            Acceso Administrativo
        </a>
    </div>

    <main class="flex-grow flex flex-col items-center justify-center relative z-10 px-4 text-center">
        <!-- Logo Icon -->
        <div class="mb-6 opacity-0 animate-fade-in-up">
            <span class="text-7xl drop-shadow-[0_0_15px_rgba(212,175,55,0.5)]">💈</span>
        </div>
        
        <!-- Brand Name -->
        <h1 class="text-5xl md:text-9xl font-black tracking-widest uppercase mb-4 text-transparent bg-clip-text bg-gradient-to-b from-[#FFF2CD] via-[#D4AF37] to-[#92711A] opacity-0 animate-fade-in-up delay-100 drop-shadow-xl">
            JyM
        </h1>
        <h2 class="text-xl md:text-3xl font-light tracking-[0.3em] uppercase mb-8 text-gray-300 opacity-0 animate-fade-in-up delay-100">
            Barbería & Perfumería
        </h2>

        <!-- Slogan -->
        <p class="text-lg md:text-2xl italic font-light text-gray-400 mb-16 opacity-0 animate-fade-in-up delay-200">
            "Atrae y seduce al instante"
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-6 w-full max-w-2xl justify-center opacity-0 animate-fade-in-up delay-300">
            <a href="{{ route('register') }}" class="group relative px-8 py-4 bg-gradient-to-r from-[#D4AF37] to-[#B38D1C] rounded-full text-black font-extrabold uppercase tracking-wider text-sm transition-all shadow-[0_0_20px_rgba(212,175,55,0.4)] hover:shadow-[0_0_30px_rgba(212,175,55,0.7)] hover:scale-105 flex items-center justify-center border border-[#FFE173]">
                <span>Agendar mi Cita</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>

            <a href="{{ route('login') }}" class="px-8 py-4 bg-[#0A0A0A] border border-[#D4AF37] rounded-full text-[#D4AF37] font-extrabold uppercase tracking-wider text-sm transition-all hover:bg-[#D4AF37]/10 hover:shadow-[0_0_20px_rgba(212,175,55,0.2)] hover:scale-105 flex items-center justify-center">
                Acceso Clientes
            </a>
        </div>
    </main>

    <!-- Footer -->
    <footer class="relative z-10 py-6 text-center border-t border-[#D4AF37]/20 bg-black/50 backdrop-blur-sm mt-auto">
        <p class="text-gray-500 text-xs font-bold tracking-widest uppercase">
            &copy; {{ date('Y') }} JyM Barbería & Perfumería. Todos los derechos reservados.
        </p>
    </footer>

</body>
</html>

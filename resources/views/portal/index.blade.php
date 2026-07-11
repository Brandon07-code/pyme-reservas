@extends('layouts.client')

@section('title', 'Catálogo Comercial')

@section('content')

    <!-- SECCIÓN DE BIENVENIDA -->
    <div class="bg-black rounded-xl shadow-2xl p-10 mb-10 text-center border-t-4 border-[#D4AF37] relative overflow-hidden">
        <!-- Efecto de brillo de fondo -->
        <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-b from-gray-800 to-black opacity-50"></div>
        
        <div class="relative z-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-white mb-3 uppercase tracking-widest">
                Bienvenido a <span class="text-[#D4AF37]">JyM</span>
            </h1>
            <p class="text-gray-300 max-w-2xl mx-auto text-lg mb-8 italic">"Atrae y seduce al instante"</p>
            <div class="mt-6">
                <!-- Este botón aún no hace nada, lo conectaremos en el siguiente bloque -->
                <a href="{{ route('portal.agendar') }}" class="bg-[#D4AF37] hover:bg-yellow-500 text-black font-extrabold py-3 px-8 rounded-full shadow-lg transition transform hover:scale-105 uppercase tracking-wide">
                    🗓️ Agendar mi Cita
                </a>
            </div>
        </div>
    </div>

    <!-- SECCIÓN DE SERVICIOS -->
    <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center"><span class="text-[#D4AF37] mr-3 text-4xl">✂️</span> Nuestros Servicios</h2>
    
    @foreach($categoriasServicios as $categoria)
        @if($categoria->services->count() > 0)
            <h3 class="text-xl font-bold text-gray-500 mb-4 ml-2 uppercase tracking-wider">{{ $categoria->nombre }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-10">
                @foreach($categoria->services as $servicio)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 flex flex-col group">
                        <div class="h-48 w-full bg-black relative overflow-hidden">
                            <img src="{{ $servicio->imagen_url ? asset($servicio->imagen_url) : 'https://via.placeholder.com/400x300/111827/D4AF37?text=JyM' }}" 
                                 alt="{{ $servicio->nombre }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500 opacity-90">
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $servicio->nombre }}</h4>
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $servicio->descripcion ?? 'Servicio profesional de alta calidad.' }}</p>
                            
                            <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-100">
                                <span class="text-xl font-extrabold text-[#D4AF37]">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                <span class="bg-gray-900 text-[#D4AF37] text-xs font-bold px-3 py-1 rounded-full">⏱ {{ $servicio->duracion_minutos }} min</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- SECCIÓN DE PERFUMERÍA -->
    <div class="mt-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 flex items-center"><span class="text-[#D4AF37] mr-3 text-4xl">✨</span> Perfumería Exclusiva</h2>
        
        @if($productos->isEmpty())
            <p class="text-gray-500">Próximamente catálogo de perfumería.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($productos as $producto)
                    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition overflow-hidden border border-gray-100 flex flex-col group">
                        <div class="h-56 w-full bg-white relative p-4 border-b border-gray-50">
                            <img src="{{ $producto->imagen_url ? asset($producto->imagen_url) : 'https://via.placeholder.com/400x400/ffffff/000000?text=Perfume' }}" 
                                 alt="{{ $producto->nombre }}" class="w-full h-full object-contain drop-shadow-lg group-hover:scale-105 transition duration-300">
                            @if($producto->stock_actual <= 0)
                                <div class="absolute inset-0 bg-white/80 flex items-center justify-center">
                                    <span class="bg-red-600 text-white font-bold px-4 py-1 rounded-full text-xs uppercase tracking-widest shadow-lg">Agotado</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col bg-gray-50">
                            <p class="text-[10px] text-[#D4AF37] font-bold uppercase tracking-wider mb-1">{{ $producto->marca ?? 'Contratipo' }}</p>
                            <h4 class="text-md font-bold text-gray-900 mb-2 leading-tight">{{ $producto->nombre }}</h4>
                            <span class="text-xl font-extrabold text-black mt-auto">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
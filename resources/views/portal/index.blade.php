@extends('layouts.client')

@section('title', 'Catálogo Comercial')

@section('content')

    <!-- SECCIÓN DE BIENVENIDA -->
    <div class="bg-white rounded-xl shadow-lg p-8 mb-10 text-center border-t-4 border-indigo-600">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Bienvenido a JYM Barbería</h1>
        <p class="text-gray-600 max-w-2xl mx-auto">Explora nuestro catálogo de servicios de barbería profesional y nuestra colección exclusiva de perfumería inspirada. ¡Agenda tu cita hoy mismo!</p>
        <div class="mt-6">
            <a href="#" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:scale-105">
                🗓️ Agendar una Cita Ahora
            </a>
        </div>
    </div>

    <!-- SECCIÓN DE SERVICIOS -->
    <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><span class="text-indigo-600 mr-2">✂️</span> Nuestros Servicios</h2>
    
    @foreach($categoriasServicios as $categoria)
        @if($categoria->services->count() > 0)
            <h3 class="text-xl font-bold text-gray-700 mb-4 ml-2 border-b-2 border-indigo-100 inline-block">{{ $categoria->nombre }}</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-8">
                @foreach($categoria->services as $servicio)
                    <div class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden border border-gray-100 flex flex-col">
                        <div class="h-40 w-full bg-gray-100 relative">
                            <img src="{{ $servicio->imagen_url ? asset($servicio->imagen_url) : 'https://via.placeholder.com/400x300?text=Servicio+JYM' }}" 
                                 alt="{{ $servicio->nombre }}" class="w-full h-full object-cover">
                        </div>
                        <div class="p-5 flex-1 flex flex-col">
                            <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $servicio->nombre }}</h4>
                            <p class="text-xs text-gray-500 mb-4 line-clamp-2">{{ $servicio->descripcion ?? 'Servicio profesional JYM' }}</p>
                            
                            <div class="flex justify-between items-center mt-auto">
                                <span class="text-xl font-extrabold text-indigo-700">${{ number_format($servicio->precio, 0, ',', '.') }}</span>
                                <span class="bg-gray-100 text-gray-600 text-xs font-bold px-2 py-1 rounded-md">⏱ {{ $servicio->duracion_minutos }} min</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    @endforeach

    <!-- SECCIÓN DE PERFUMERÍA -->
    <div class="mt-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 flex items-center"><span class="text-indigo-600 mr-2">✨</span> Perfumería Exclusiva</h2>
        
        @if($productos->isEmpty())
            <p class="text-gray-500">Próximamente catálogo de perfumería.</p>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">
                @foreach($productos as $producto)
                    <div class="bg-white rounded-xl shadow hover:shadow-xl transition overflow-hidden border border-gray-100 flex flex-col">
                        <div class="h-48 w-full bg-white relative p-4">
                            <img src="{{ $producto->imagen_url ? asset($producto->imagen_url) : 'https://via.placeholder.com/400x400?text=Perfume' }}" 
                                 alt="{{ $producto->nombre }}" class="w-full h-full object-contain drop-shadow-md">
                            @if($producto->stock_actual <= 0)
                                <div class="absolute inset-0 bg-white/70 flex items-center justify-center">
                                    <span class="bg-red-600 text-white font-bold px-3 py-1 rounded-full text-xs">AGOTADO</span>
                                </div>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col border-t border-gray-50">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">{{ $producto->marca ?? 'Contratipo' }}</p>
                            <h4 class="text-md font-bold text-gray-900 mb-2 leading-tight">{{ $producto->nombre }}</h4>
                            <span class="text-xl font-extrabold text-indigo-700 mt-auto">${{ number_format($producto->precio, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection
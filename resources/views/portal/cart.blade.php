@extends('layouts.client')

@section('title', 'Mi Carrito')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="bg-black p-6 rounded-t-xl border-b-4 border-[#D4AF37]">
            <h1 class="text-3xl font-extrabold text-white uppercase tracking-widest">Mi Carrito de Compras</h1>
            <p class="text-[#D4AF37] font-semibold text-sm mt-1">Reserva tus perfumes y págalos en la barbería</p>
        </div>

        <div class="bg-white shadow-xl rounded-b-xl p-8 border border-gray-100">
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded shadow-sm"><p class="font-bold">{{ session('success') }}</p></div>
            @endif

            @if(count($cart) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b-2 border-gray-200">
                                <th class="py-3 px-4 font-bold text-gray-700 uppercase text-xs">Producto</th>
                                <th class="py-3 px-4 font-bold text-gray-700 uppercase text-xs">Precio Unitario</th>
                                <th class="py-3 px-4 font-bold text-gray-700 uppercase text-xs text-center">Cantidad</th>
                                <th class="py-3 px-4 font-bold text-gray-700 uppercase text-xs text-right">Subtotal</th>
                                <th class="py-3 px-4"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $id => $details)
                                <tr class="border-b border-gray-100 hover:bg-gray-50">
                                    <td class="py-4 px-4 flex items-center">
                                        <div class="h-12 w-12 bg-white border border-gray-200 rounded flex-shrink-0 mr-4">
                                            <img src="{{ $details['imagen_url'] ? asset($details['imagen_url']) : 'https://via.placeholder.com/150' }}" class="w-full h-full object-contain p-1">
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $details['nombre'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $details['marca'] }}</p>
                                        </div>
                                    </td>
                                    <td class="py-4 px-4 text-gray-600">${{ number_format($details['precio'], 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-center font-bold text-gray-800">{{ $details['cantidad'] }}</td>
                                    <td class="py-4 px-4 text-right font-bold text-[#D4AF37]">${{ number_format($details['precio'] * $details['cantidad'], 0, ',', '.') }}</td>
                                    <td class="py-4 px-4 text-right">
                                        <form action="{{ route('portal.cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $id }}">
                                            <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-xs uppercase tracking-wider">X Quitar</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 flex flex-col md:flex-row justify-between items-center bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <div class="mb-4 md:mb-0">
                        <a href="{{ route('portal.index') }}" class="text-indigo-600 font-semibold hover:underline">&larr; Seguir explorando</a>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 font-bold uppercase mb-1">Total a Pagar en Local:</p>
                        <p class="text-4xl font-extrabold text-black mb-4">${{ number_format($total, 0, ',', '.') }}</p>
                        
                       <form action="{{ route('portal.cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" onclick="return confirm('¿Confirmas la reserva de estos productos? Recuerda que tienes 24 horas para recogerlos en nuestra sede.');" class="bg-[#D4AF37] hover:bg-yellow-500 text-black font-extrabold py-3 px-8 rounded-full shadow-lg transition uppercase tracking-wide w-full md:w-auto">
                                Confirmar Pedido
                            </button>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <p class="text-gray-500 mb-6 text-lg">Tu carrito está vacío.</p>
                    <a href="{{ route('portal.index') }}" class="bg-black hover:bg-gray-900 text-[#D4AF37] font-bold py-3 px-8 rounded-full shadow-lg transition uppercase tracking-wide">
                        Ir al Catálogo
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
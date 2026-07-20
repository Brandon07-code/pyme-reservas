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

                <div class="mt-8 flex flex-col md:flex-row justify-between items-center bg-gray-50 p-6 rounded-lg">
                    <div class="mb-4 md:mb-0">
                        <a href="{{ route('portal.index') }}" class="text-indigo-600 font-semibold hover:underline">&larr; Seguir explorando</a>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500 font-bold uppercase mb-1">Total a Pagar en Local:</p>
                        <p class="text-4xl font-extrabold text-black mb-4">${{ number_format($total, 0, ',', '.') }}</p>

                        {{-- Botón que abre el modal (ya NO usa confirm() nativo) --}}
                        <button type="button" onclick="document.getElementById('modalConfirmar').classList.remove('hidden')"
                            class="bg-[#D4AF37] hover:bg-yellow-500 text-black font-extrabold py-3 px-8 rounded-full shadow-lg transition uppercase tracking-wide w-full md:w-auto">
                            Confirmar Pedido
                        </button>

                        {{-- Formulario oculto que solo se envía si el cliente confirma en el modal --}}
                        <form id="formCheckout" action="{{ route('portal.cart.checkout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
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

    {{-- ===== MODAL DE CONFIRMACIÓN PERSONALIZADO ===== --}}
    <div id="modalConfirmar" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.85);">
        <div class="bg-[#111111] rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">

            {{-- Icono de carrito --}}
            <div class="flex justify-center mb-5">
                <div class="bg-[#D4AF37] rounded-full p-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-black" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>

            {{-- Título --}}
            <h2 class="text-2xl font-extrabold text-white uppercase tracking-wide mb-3">¿Confirmar tu pedido?</h2>

            {{-- Descripción --}}
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                Estás a punto de reservar tu selección de productos. Una vez confirmado, el equipo de
                <span class="text-[#D4AF37] font-semibold">JyM Barbería</span>
                lo tendrá listo para que lo recojas en nuestra sede.<br><br>
                <span class="text-yellow-400 font-semibold">Recuerda: Una vez se confirme el pedido, tienes 24 horas para recogerlo.</span>
            </p>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                {{-- Cancelar --}}
                <button type="button" onclick="document.getElementById('modalConfirmar').classList.add('hidden')"
                    class="flex-1 py-3 px-6 rounded-full font-bold text-gray-400 hover:text-white transition uppercase tracking-wide text-sm" style="background:#222;">
                    Cancelar
                </button>
                {{-- Confirmar definitivo --}}
                <button type="button" onclick="document.getElementById('formCheckout').submit()"
                    class="flex-1 py-3 px-6 rounded-full font-extrabold text-black bg-[#D4AF37] hover:bg-yellow-500 transition uppercase tracking-wide text-sm shadow-lg">
                    Sí, confirmar pedido →
                </button>
            </div>
        </div>
    </div>
    {{-- ===== FIN MODAL ===== --}}

@endsection
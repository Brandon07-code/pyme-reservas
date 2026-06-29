@extends('layouts.app')
@section('title', 'Servicios')
@section('content')
    <div class="bg-white shadow rounded-lg p-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">Catálogo de Servicios</h1>
        <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Nuevo Servicio</button>
    </div>
    <div class="mt-4 bg-gray-50 border border-dashed border-gray-300 p-8 text-center text-gray-500 rounded-lg">
        [Tabla de listado de servicios]
    </div>
@endsection
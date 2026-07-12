@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            Integración API Externa (JSONPlaceholder)
        </h2>
    </div>

    {{-- Manejo de Errores de la API --}}
    @if(isset($error))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 shadow-md" role="alert">
            <p class="font-bold">Error de Conexión</p>
            <p>{{ $error }}</p>
        </div>
    @else
        {{-- Grid de Tarjetas con los Posts consumidos --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($posts as $post)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <span class="text-xs font-semibold inline-block py-1 px-2 uppercase rounded text-indigo-600 bg-indigo-200 uppercase last:mr-0 mr-1 mb-2">
                        Post ID: {{ $post['id'] }}
                    </span>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 capitalize">
                        {{ $post['title'] }}
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">
                        {{ Str::limit($post['body'], 100) }}
                    </p>
                </div>
            @empty
                <div class="col-span-3 text-center py-10">
                    <p class="text-gray-500 dark:text-gray-400">No se recibieron datos de la API externa.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
@endsection
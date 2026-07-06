@props(['title', 'createRoute' => null, 'buttonText' => '+ Nuevo'])

<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold text-gray-800">{{ $title }}</h1>
    @if($createRoute)
        <a href="{{ $createRoute }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
            {{ $buttonText }}
        </a>
    @endif
</div>
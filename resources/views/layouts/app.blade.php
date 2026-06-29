<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PYME Reservas - @yield('title', 'Inicio')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans leading-normal tracking-normal text-gray-800">
    
    <nav class="bg-gray-900 p-4 w-full shadow">
        <div class="container mx-auto flex flex-wrap items-center justify-between">
            <div class="flex justify-center md:justify-start font-extrabold text-white">
                <a class="text-white no-underline hover:text-gray-300 hover:no-underline" href="/">
                    <span class="text-xl pl-2">💈 PYME Reservas</span>
                </a>
            </div>
            <div class="flex w-full pt-2 content-center justify-between md:w-auto md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center text-sm">
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/">Inicio</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/usuarios">Usuarios</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/empleados">Empleados</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/clientes">Clientes</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/servicios">Servicios</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/productos">Productos</a></li>
                    <li class="mr-3"><a class="inline-block text-white no-underline hover:text-gray-400 py-2 px-2" href="/reservas">Reservas</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mx-auto mt-8 p-4">
        @yield('content')
    </div>

</body>
</html>
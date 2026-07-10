<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PYME Reservas - Acceso</title>
    <!-- Forzamos Tailwind sin modo oscuro -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Desactivamos el dark mode globalmente para esta vista */
        @media (prefers-color-scheme: dark) {
            body { background-color: #f3f4f6 !important; color: #1f2937 !important; }
        }
    </style>
</head>
<body class="font-sans text-gray-900 antialiased bg-gray-100 flex flex-col sm:justify-center items-center min-h-screen pt-6 sm:pt-0">
    
    <div>
        <a href="/" class="flex items-center justify-center text-4xl font-extrabold text-gray-900 mb-6 hover:text-indigo-600 transition">
            <span class="mr-3">💈</span> PYME Reservas
        </a>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl overflow-hidden sm:rounded-xl border border-gray-100">
        {{ $slot }}
    </div>

</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Terrenos</title>
    <link rel="preload" href="{{ asset('images/terrenos-login.jpg') }}" as="image">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="antialiased">
    <div class="relative min-h-screen bg-cover bg-center flex items-center justify-center p-4" style="background-image: url('{{ asset('images/terrenos-login.jpg') }}');">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="absolute top-0 left-0 p-6">
            <a href="{{ url('/') }}"
               class="flex items-center gap-2 text-white/80 hover:text-white transition-colors duration-200 group">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                     class="w-5 h-5 transition-transform duration-200 group-hover:-translate-x-1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
                <span>Volver al Inicio</span>
            </a>
        </div>
        <div class="relative w-full max-w-md bg-black/20 backdrop-blur-lg rounded-xl shadow-2xl p-8 sm:p-10 border border-white/20">
            <div class="text-center mb-8">
                <h1 class="font-playfair text-4xl font-bold text-white tracking-tight">
                    Bienvenido
                </h1>
                <p class="text-stone-300 mt-2">Acceso al Sistema POS</p>
            </div>
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-500/30 border border-red-500 text-white rounded-lg text-sm">
                    <p class="font-bold">Error de Acceso</p>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-stone-200">Correo Electrónico</label>
                    <div class="mt-1">
                        {{-- CAMBIO: De "off" a "new-password" para engañar al navegador --}}
                        <input id="email" name="email" type="email" autocomplete="new-password" required
                               class="block w-full px-4 py-2 bg-white/5 border border-white/20 rounded-lg text-white placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent sm:text-sm"
                               autofocus>
                    </div>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-stone-200">Contraseña</label>
                    <div class="mt-1">
                        {{-- CAMBIO: De "off" a "new-password" para engañar al navegador --}}
                        <input id="password" name="password" type="password" autocomplete="new-password" required
                               class="block w-full px-4 py-2 bg-white/5 border border-white/20 rounded-lg text-white placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent sm:text-sm">
                    </div>
                </div>
                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-semibold text-amber-950 bg-amber-500 hover:bg-amber-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500 transition duration-150 ease-in-out">
                        Iniciar Sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

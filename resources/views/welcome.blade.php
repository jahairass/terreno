<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Panadería') }}</title>
    {{-- PASO EXTRA: Añadimos la fuente "Playfair Display" para el título --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- PASO EXTRA: Añadimos la fuente "Playfair Display" para el título --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;600&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- PASO EXTRA: Añadimos la nueva fuente a la configuración de Tailwind --}}
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>
<body class="font-sans antialiased">
    {{-- Contenedor principal con posicionamiento relativo para que la imagen de fondo se ajuste a él --}}
    <div class="relative min-h-screen">
        {{-- Capa 1: La imagen de fondo y la superposición oscura --}}
        <div class="absolute inset-0 -z-10">
            {{-- La imagen ocupa todo el espacio disponible --}}
            <img src="{{ asset('images/terreno_hero.jpg') }}" alt="Fondo de panes artesanales" class="h-full w-full object-cover">
            {{-- La superposición oscura para asegurar que el texto sea legible --}}
            <div class="absolute inset-0 bg-black/70"></div> {{-- bg-black/70 es negro con 70% de opacidad --}}
        </div>
        {{-- Capa 2: El contenido centrado --}}
        <div class="relative min-h-screen flex flex-col items-center justify-center text-center text-white px-6">
            {{-- Header superior, ahora solo para el botón de Dashboard si está logueado --}}
            <header class="absolute top-0 right-0 p-8">
                @if (Route::has('login'))
                    <nav>
                        @auth
                            <a
                                href="{{ url('/dashboard') }}"
                                class="rounded-md px-3 py-2 text-white ring-1 ring-white/20 transition hover:text-white/70 focus:outline-none focus-visible:ring-amber-500"
                            >
                                Panel de Control
                            </a>
                        @endauth
                    </nav>
                @endif
            </header>
            {{-- Contenido principal --}}
            <main>
                <h1 class="font-playfair text-6xl lg:text-8xl font-bold tracking-tight">
                    Compra venta de terrenos
                </h1>
                <p class="mt-6 text-lg max-w-xl mx-auto text-stone-300">
                    El mejor lugar para tu proyecto está aquí.
                </p>
                <div class="mt-8 flex items-center justify-center">
                    @if (Route::has('login'))
                        @guest
                            <a href="{{ route('login') }}" class="rounded-md bg-amber-500 px-5 py-3 text-sm font-semibold text-amber-950 shadow-sm transition-transform hover:scale-105 hover:bg-amber-400 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500">
                                Iniciar Sesión
                            </a>
                        @endguest
                    @endif
                </div>
            </main>
        </div>
    </div>
</body>
</html>

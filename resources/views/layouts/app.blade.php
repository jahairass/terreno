<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Terrenos POS') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    {{-- CAMBIO: Eliminada la línea duplicada y corrupta de Font Awesome de arriba --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

    <nav class="top-header navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-dark me-3" id="sidebarToggle" type="button">
                <i class="fas fa-bars"></i>
            </button>

            <a class="navbar-brand me-auto" href="{{ route('dashboard') }}">
                <i class="fas fa-bread-slice me-2"></i> {{ config('app.name', 'Panadería') }} POS
            </a>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i> {{ Auth::user()->name ?? 'Usuario' }}
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-cog me-2"></i> Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <div class="sidebar" id="sidebar">
        @include('layouts.navigation')
    </div>

    <div class="main-content" id="main-content">
        <div id="app">

            {{-- CAMBIO: Añadida la clase 'auto-dismiss-alert' --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show auto-dismiss-alert" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- CAMBIO: Añadida la clase 'auto-dismiss-alert' --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show auto-dismiss-alert" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <main>
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Script del Sidebar
            document.getElementById('sidebarToggle').addEventListener('click', function () {
                document.getElementById('sidebar').classList.toggle('collapsed');
                document.getElementById('main-content').classList.toggle('collapsed');
            });

            // ==========================================================
            // CAMBIO: Script para auto-cerrar las alertas
            // ==========================================================

            // 1. Seleccionar todas las alertas que deben auto-cerrarse
            const autoDismissAlerts = document.querySelectorAll('.auto-dismiss-alert');

            // 2. Para cada alerta, establecer un temporizador de 5 segundos
            autoDismissAlerts.forEach(function(alert) {
                setTimeout(function() {
                    // 3. Usar la API de Bootstrap para cerrar la alerta con la animación
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 2500); // 5000 milisegundos = 5 segundos
            });

        });
    </script>
@stack('scripts')</body>
</html>

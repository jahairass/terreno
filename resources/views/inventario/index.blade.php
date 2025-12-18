@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="mb-4 fw-bold">
        <i class="fas fa-map-marked-alt me-2"></i> Mapa de Terrenos Disponibles
    </h3>

    <div class="row g-4">

        @foreach ($productos as $producto)

            @php
                // Colores según estado
                switch ($producto->estado) {
                    case 'disponible':
                        $bg = 'bg-success';
                        $badge = 'Disponible';
                        break;
                    case 'proceso':
                        $bg = 'bg-warning';
                        $badge = 'En Proceso';
                        break;
                    case 'vendido':
                        $bg = 'bg-dark';
                        $badge = 'Vendido';
                        break;
                    default:
                        $bg = 'bg-secondary';
                        $badge = 'Sin estado';
                }
            @endphp

            <div class="col-md-4">
                <div class="card text-white shadow-lg border-0 {{ $bg }}">
                    <div class="card-body text-center">

                        <h5 class="fw-bold mb-1">
                            {{ strtoupper($producto->nombre) }}
                        </h5>

                        <p class="mb-2">
                            {{ $producto->descripcion }}
                        </p>

                        {{-- Nivel --}}
                        <span class="badge bg-light text-dark mb-2">
                            {{ strtoupper($producto->nivel) }}
                        </span>

                        <div class="mt-2">
                            <span class="badge bg-white text-dark">
                                {{ $badge }}
                            </span>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('inventario.edit', $producto->id) }}"
                               class="btn btn-outline-light btn-sm">
                                Ajustar
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        @endforeach

    </div>

    {{-- Leyenda --}}
    <div class="mt-4">
        <span class="badge bg-success me-2">Disponible</span>
        <span class="badge bg-warning text-dark me-2">En Proceso</span>
        <span class="badge bg-dark">Vendido</span>
    </div>

</div>
@endsection


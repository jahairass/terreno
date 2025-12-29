@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background:#ffffff;min-height:100vh;">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-4"
         style="
            background:linear-gradient(135deg,#0a2540,#0f3d63);
            border-radius:18px;
            border:1px solid rgba(255,255,255,.10);
            box-shadow:0 20px 40px rgba(0,0,0,.6);
         ">
        <div>
            <div class="text-uppercase"
                 style="letter-spacing:.18em;font-size:.75rem;color:#93c5fd;">
                Inmobiliaria • Clientes
            </div>
            <h3 class="fw-bold mb-0" style="color:#e6edf7;">
                Módulo de Clientes
            </h3>
        </div>

        @if(Auth::user()->hasPermissionTo('clientes', 'alta'))
            <a href="{{ route('clientes.create') }}"
               class="btn"
               style="
                    background:#2563eb;
                    color:#ffffff;
                    font-weight:700;
                    border-radius:14px;
               ">
                <i class="fas fa-user-plus me-1"></i> Nuevo Cliente
            </a>
        @endif
    </div>

    {{-- Tarjetas --}}
    <div class="row g-4">
        @forelse($clientes as $cliente)
        <div class="col-md-4">
            <div class="card h-100 border-0"
                 style="
                    border-radius:22px;
                    background:#07101e;
                    color:#e6edf7;
                    border:1px solid rgba(255,255,255,.10);
                    box-shadow:0 15px 35px rgba(0,0,0,.65);
                    transition:.25s;
                 "
                 onmouseover="this.style.transform='translateY(-6px)'"
                 onmouseout="this.style.transform='translateY(0)'"
            >
                <div class="card-body p-4">

                    {{-- Nombre --}}
                    <h5 class="fw-bold mb-3" style="color:#38bdf8;">
                        {{ $cliente->Nombre }}
                    </h5>

                    <div style="font-size:.95rem;">
                        <p class="mb-1"><strong style="color:#93c5fd;">ID:</strong> {{ $cliente->id }}</p>
                        <p class="mb-1"><strong style="color:#93c5fd;">Correo:</strong> {{ $cliente->correo ?? '—' }}</p>
                        <p class="mb-1"><strong style="color:#93c5fd;">Teléfono:</strong> {{ $cliente->telefono ?? '—' }}</p>
                        <p class="mb-1"><strong style="color:#93c5fd;">Identificación:</strong> {{ $cliente->identificacion ?? '—' }}</p>
                        <p class="mb-1"><strong style="color:#93c5fd;">Dirección:</strong> {{ $cliente->direccion ?? '—' }}</p>
                        <p class="mb-0" style="color:#64748b;">
                            <strong>Fecha:</strong> {{ $cliente->fecha_compra ?? '—' }}
                        </p>
                    </div>

                    <hr style="border-color:rgba(255,255,255,.12);margin:1.2rem 0;">

                    {{-- Acciones --}}
                    <div class="d-flex justify-content-between">
                        @if(Auth::user()->hasPermissionTo('clientes', 'editar'))
                        <a href="{{ route('clientes.edit', $cliente->id) }}"
                           class="btn btn-sm"
                           style="
                                background:#0a2540;
                                color:#e6edf7;
                                border-radius:10px;
                           ">
                            ✏️ Editar
                        </a>
                        @endif

                        @if(Auth::user()->hasPermissionTo('clientes', 'eliminar'))
                        <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button
                                class="btn btn-sm"
                                style="
                                    background:#991b1b;
                                    color:#ffffff;
                                    border-radius:10px;
                                "
                                onclick="return confirm('¿Eliminar cliente?')">
                                🗑️ Eliminar
                            </button>
                        </form>
                        @endif
                    </div>

                </div>
            </div>
        </div>
        @empty
            <div class="col-12">
                <div class="alert"
                     style="background:#07101e;color:#93c5fd;border:1px solid rgba(255,255,255,.10);">
                    No hay clientes registrados.
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

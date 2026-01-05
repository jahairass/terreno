@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3 p-3"
         style="background:#000; border-radius:10px;">
        <h2 class="mb-0 fw-bold" style="color:yellow;">
            Inventario - Terrenos
        </h2>

        <a href="{{ route('inventario.create') }}"
           class="btn btn-warning fw-bold"
           style="border-radius:10px;">
            + Agregar Terreno
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ Barra de clientes (chips) --}}
    <div class="mb-4 p-3" style="background:#f3f4f6; border-radius:12px;">
        <div class="d-flex flex-wrap gap-2 align-items-center">

            {{-- Todos --}}
            <a href="{{ route('inventario.index') }}"
               class="badge text-decoration-none px-3 py-2"
               style="border-radius:999px; font-size:.9rem; background:{{ empty($clienteFiltro) ? '#111827' : '#e5e7eb' }}; color:{{ empty($clienteFiltro) ? 'white' : '#111827' }};">
                Todos
            </a>

            @forelse($clientes as $cli)
                <a href="{{ route('inventario.index', ['cliente' => $cli]) }}"
                   class="badge text-decoration-none px-3 py-2"
                   style="border-radius:999px; font-size:.9rem; background:{{ ($clienteFiltro === $cli) ? '#f59e0b' : '#e5e7eb' }}; color:#111827;">
                    {{ $cli }}
                </a>
            @empty
                <span class="text-muted">Aún no hay clientes registrados.</span>
            @endforelse
        </div>

        @if(!empty($clienteFiltro))
            <div class="mt-2" style="font-size:.9rem;">
                Mostrando terrenos del cliente: <strong>{{ $clienteFiltro }}</strong>
            </div>
        @endif
    </div>

    <div class="row g-3">
        @forelse($terrenos as $t)
            @php
                $bg = '#e5e7eb';
                $text = '#111827';

                if ($t->estado === 'disponible') { $bg = '#16a34a'; $text = 'white'; }
                elseif ($t->estado === 'reservado') { $bg = '#f59e0b'; $text = '#111827'; }
                elseif ($t->estado === 'vendido') { $bg = '#ef4444'; $text = 'white'; }
            @endphp

            <div class="col-12 col-md-6 col-lg-4">
                <div style="border-radius:14px;padding:14px;background:{{ $bg }};color:{{ $text }};min-height:190px;box-shadow:0 2px 6px rgba(0,0,0,.10);">
                    <div class="fw-bold" style="font-size:1.05rem;">
                        {{-- ✅ CAMBIO: codigo -> cliente --}}
                        {{ $t->cliente }}
                    </div>

                    <div class="mt-2" style="font-size:.85rem;">
                        <div><strong>Alcaldía:</strong> {{ $t->alcaldia ?? '-' }}</div>
                        <div><strong>Ubicación:</strong> {{ $t->ubicacion ?? '-' }}</div>
                        <div><strong>Precio:</strong> ${{ number_format($t->precio ?? 0, 2) }}</div>
                        <div><strong>Estado:</strong> {{ strtoupper($t->estado) }}</div>
                    </div>

                    <div class="mt-3 d-flex gap-2">
                        <a href="{{ route('inventario.edit', $t->id) }}"
                           class="btn btn-dark btn-sm w-100"
                           style="border-radius:10px;">
                            Editar
                        </a>

                        <form action="{{ route('inventario.destroy', $t->id) }}"
                              method="POST"
                              class="w-100"
                              onsubmit="return confirm('¿Seguro que deseas eliminar este terreno?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm w-100" style="border-radius:10px;">
                                Eliminar
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    Aún no hay terrenos registrados. Da clic en <strong>+ Agregar Terreno</strong>.
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection

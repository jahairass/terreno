@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3"
         style="background:#000; border-radius:10px;">
        <h2 class="mb-0 fw-bold" style="color:yellow;">
            Inventario - Mapa de Terrenos
        </h2>

        <div class="d-flex gap-2">
            <span class="badge" style="background:#16a34a;">Disponible</span>
            <span class="badge text-dark" style="background:#f59e0b;">En proceso</span>
            <span class="badge" style="background:#ef4444;">Vendido</span>
        </div>
    </div>

    {{-- Mensaje éxito --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- MAPA (GRID) --}}
    <div class="p-3" style="background:#f3f4f6; border-radius:12px;">
        <div style="
            display:grid;
            grid-template-columns: repeat({{ $maxCol }}, minmax(150px, 1fr));
            gap:12px;
        ">
            @for ($f = 1; $f <= $maxFila; $f++)
                @for ($c = 1; $c <= $maxCol; $c++)
                    @php
                        $key = $f . '-' . $c;
                        $t = $porCelda->get($key);

                        // color por estado
                        $bg = '#e5e7eb';      // vacío
                        $text = '#111827';

                        if ($t) {
                            if ($t->estado === 'disponible') { $bg = '#16a34a'; $text = 'white'; }
                            elseif ($t->estado === 'en_proceso') { $bg = '#f59e0b'; $text = '#111827'; }
                            elseif ($t->estado === 'vendido') { $bg = '#ef4444'; $text = 'white'; }
                            else { $bg = '#6b7280'; $text = 'white'; }
                        }

                        $estadoTxt = $t ? strtoupper(str_replace('_',' ', $t->estado)) : '';
                    @endphp

                    <div style="
                        border-radius:12px;
                        padding:12px;
                        background:{{ $bg }};
                        color:{{ $text }};
                        min-height:150px;
                        box-shadow: 0 1px 2px rgba(0,0,0,.08);
                    ">
                        <div class="fw-bold" style="font-size: 1rem;">
                            {{ $t ? $t->codigo : 'VACÍO' }}
                        </div>

                        <div style="font-size:.85rem; opacity:.95;">
                            F{{ $f }} C{{ $c }}
                        </div>

                        @if($t)
                            <div class="mt-2" style="font-size:.85rem;">
                                <div><strong>Alcaldía:</strong> {{ $t->alcaldia ?? '-' }}</div>
                                <div><strong>Ubicación:</strong> {{ $t->ubicacion ?? '-' }}</div>
                                <div><strong>Precio:</strong> ${{ number_format($t->precio ?? 0, 2) }}</div>
                                <div><strong>Estado:</strong> {{ $estadoTxt }}</div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('inventario.edit', $t->id) }}"
                                   class="btn btn-dark btn-sm w-100"
                                   style="border-radius:10px;">
                                    Editar
                                </a>
                            </div>
                        @else
                            <div class="mt-3" style="font-size:.85rem; opacity:.85;">
                                Sin terreno asignado
                            </div>
                        @endif
                    </div>

                @endfor
            @endfor
        </div>
    </div>

</div>
@endsection

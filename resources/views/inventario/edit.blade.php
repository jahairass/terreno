@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Card centrado --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        
        {{-- Cabecera oscura --}}
        <div class="card-header bg-dark text-white border-0">
            <h4 class="mb-0">Ajuste de Terreno: {{ $producto->nombre }}</h4>
            <small>Categoría: {{ $producto->categoria->nombre ?? 'N/A' }}</small>
        </div>

        {{-- Card body --}}
        <div class="card-body p-4">

            <form action="{{ route('inventario.update', $producto->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Sección Estado --}}
                <h6 class="text-muted">Estado del Terreno</h6>
                <hr class="mt-1 mb-3 border-secondary">

                <div class="mb-3">
                    <label class="form-label fw-bold">Estado Actual</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-flag"></i>
                        </span>
                        <select name="estado" class="form-select" required>
                            <option value="disponible" {{ $producto->estado == 'disponible' ? 'selected' : '' }}>
                                Disponible
                            </option>
                            <option value="proceso" {{ $producto->estado == 'proceso' ? 'selected' : '' }}>
                                En Proceso
                            </option>
                            <option value="vendido" {{ $producto->estado == 'vendido' ? 'selected' : '' }}>
                                Vendido
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Sección Nivel --}}
                <h6 class="text-muted mt-4">Nivel del Terreno</h6>
                <hr class="mt-1 mb-3 border-secondary">

                <div class="mb-4">
                    <label class="form-label fw-bold">Nivel</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fas fa-layer-group"></i>
                        </span>
                        <select name="nivel" class="form-select" required>
                            <option value="basico" {{ $producto->nivel == 'basico' ? 'selected' : '' }}>
                                Básico
                            </option>
                            <option value="plus" {{ $producto->nivel == 'plus' ? 'selected' : '' }}>
                                Plus
                            </option>
                            <option value="premium" {{ $producto->nivel == 'premium' ? 'selected' : '' }}>
                                Premium
                            </option>
                        </select>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>

                    @if (Auth::user()->hasPermissionTo('inventario', 'editar'))
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save me-1"></i> Guardar Ajustes
                        </button>
                    @else
                        <span class="text-danger">No tienes permiso para editar.</span>
                    @endif
                </div>

            </form>
        </div>
    </div>
</div>
@endsection




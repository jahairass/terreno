@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Card centrado --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">

        {{-- CAMBIO: Cabecera oscura y h4 para el título --}}
        <div class="card-header bg-dark text-white border-0">
            <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Registrar Nuevo Cliente</h4>
        </div>

        {{-- CAMBIO: card-body con p-4 y sin alerta de sesión --}}
        <div class="card-body p-4">

            <form action="{{ route('clientes.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="Nombre" class="form-label">Nombre del Cliente</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                        <input type="text" class="form-control @error('Nombre') is-invalid @enderror" id="Nombre" name="Nombre" value="{{ old('Nombre') }}" required>
                    </div>
                    @error('Nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Teléfono --}}
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono') }}">
                    </div>
                    @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Correo --}}
                <div class="mb-3">
                    <label for="correo" class="form-label">Correo Electrónico</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope fa-fw"></i></span>
                        <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo') }}">
                    </div>
                    @error('correo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Identificación --}}
                <div class="mb-3">
                    <label for="identificacion" class="form-label">Identificación</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-plus fa-fw"></i></span>
                        <input type="text" class="form-control @error('identificacion') is-invalid @enderror" id="identificacion" name="identificacion" value="{{ old('identificacion') }}">
                    </div>
                    @error('identificacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Dirección --}}
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion') }}">
                    </div>
                    @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        {{-- CAMBIO: Ícono añadido --}}
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>

                    @if (Auth::user()->hasPermissionTo('clientes', 'alta'))
                        <button type="submit" class="btn btn-success">
                            {{-- CAMBIO: Ícono estandarizado --}}
                            <i class="fas fa-save me-1"></i> Guardar Cliente
                        </button>
                    @else
                        <span class="text-danger">No tienes permiso para registrar clientes.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

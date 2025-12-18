@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Card centrado --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        
        {{-- CAMBIO: Cabecera oscura y h4 para el título --}}
        <div class="card-header bg-dark text-white border-0">
            <h4 class="mb-0"><i class="fas fa-truck me-2"></i> Registrar Nuevo Proveedor</h4>
        </div>

        {{-- CAMBIO: card-body con p-4 y sin alerta de sesión --}}
        <div class="card-body p-4">

            <form action="{{ route('proveedores.store') }}" method="POST">
                @csrf

                {{-- CAMBIO: Sección añadida --}}
                <h6 class="text-muted">Información del Proveedor</h6>
                <hr class="mt-1 mb-3 border-secondary">

                {{-- Nombre de Contacto --}}
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Contacto</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    </div>
                    @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                {{-- Nombre de la Empresa --}}
                <div class="mb-3">
                    <label for="empresa" class="form-label">Nombre de la Empresa</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-building fa-fw"></i></span>
                        <input type="text" class="form-control @error('empresa') is-invalid @enderror" id="empresa" name="empresa" value="{{ old('empresa') }}">
                    </div>
                    @error('empresa') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
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
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                        {{-- CAMBIO: Ícono añadido --}}
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    
                    @if (Auth::user()->hasPermissionTo('proveedores', 'alta'))
                        <button type="submit" class="btn btn-success">
                            {{-- CAMBIO: Ícono estandarizado --}}
                            <i class="fas fa-save me-1"></i> Guardar Proveedor
                        </button>
                    @else
                        <span class="text-danger">No tienes permiso para registrar proveedores.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
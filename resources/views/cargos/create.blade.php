@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- Card centrado para el formulario --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 500px;">
        
        {{-- Card Header: Estilo consistente con el index --}}
        <div class="card-header bg-dark text-white border-0 border-bottom">
            <h4 class="mb-0">Crear Nuevo Cargo</h4>
        </div>

        {{-- Card Body: Añadimos más padding (p-4) --}}
        <div class="card-body p-4">

            {{-- Formulario de creación --}}
            <form action="{{ route('cargos.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Cargo</label>
                    
                    {{-- Usamos un Input Group para añadir un icono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag fa-fw"></i></span>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" value="{{ old('nombre') }}" 
                               placeholder="Ej: Administrador" required>
                        
                        @error('nombre') 
                            <div class="invalid-feedback">{{ $message }}</div> 
                        @enderror
                    </div>
                </div>
                
                {{-- Botones de acción --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    {{-- Cambiamos a btn-primary para consistencia --}}
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Guardar Cargo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
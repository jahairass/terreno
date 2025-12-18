@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-header bg-warning text-dark">
            <h3 class="mb-0">Editar Cargo: {{ $cargo->nombre }}</h3>
        </div>
        <div class="card-body">

            {{-- Formulario de edición con método PATCH/PUT --}}
            <form action="{{ route('cargos.update', $cargo->id) }}" method="POST">
                @csrf
                @method('PUT') {{-- O PATCH, depende de tu preferencia en el controlador --}}

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre del Cargo</label>
                    {{-- Precarga el valor actual --}}
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $cargo->nombre) }}" required>
                    @error('nombre') 
                        <div class="invalid-feedback">{{ $message }}</div> 
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('cargos.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-sync"></i> Actualizar Cargo
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
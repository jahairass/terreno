@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3"
         style="background:#000; border-radius:10px;">
        <h2 class="mb-0 fw-bold" style="color:yellow;">
            Editar Terreno: {{ $terreno->codigo }}
        </h2>

        <a href="{{ route('inventario.index') }}" class="btn btn-outline-warning">
            Volver al mapa
        </a>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Corrige estos errores:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('inventario.update', $terreno->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-3">

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Código</label>
                        <input type="text" name="codigo" class="form-control"
                               value="{{ old('codigo', $terreno->codigo) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alcaldía</label>
                        <input type="text" name="alcaldia" class="form-control"
                               value="{{ old('alcaldia', $terreno->alcaldia) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control"
                               value="{{ old('ubicacion', $terreno->ubicacion) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control"
                               value="{{ old('precio', $terreno->precio) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="estado" class="form-select">
                            <option value="disponible"
                                {{ old('estado', $terreno->estado) == 'disponible' ? 'selected' : '' }}>
                                Disponible
                            </option>

                            <option value="en_proceso"
                                {{ old('estado', $terreno->estado) == 'en_proceso' ? 'selected' : '' }}>
                                En proceso de venta
                            </option>

                            <option value="vendido"
                                {{ old('estado', $terreno->estado) == 'vendido' ? 'selected' : '' }}>
                                Vendido
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Fila</label>
                        <input type="number" name="fila" class="form-control"
                               value="{{ old('fila', $terreno->fila) }}" min="1">
                    </div>

                    <div class="col-md-2">
                        <label class="form-label fw-bold">Columna</label>
                        <input type="number" name="columna" class="form-control"
                               value="{{ old('columna', $terreno->columna) }}" min="1">
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold">
                        Guardar cambios
                    </button>

                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                        Cancelar
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>
@endsection


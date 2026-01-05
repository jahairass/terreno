@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Encabezado --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3"
         style="background:#000; border-radius:10px;">
        <h2 class="mb-0 fw-bold" style="color:yellow;">
            Agregar Terreno
        </h2>

        <a href="{{ route('inventario.index') }}" class="btn btn-outline-warning">
            Volver
        </a>
    </div>

    {{-- Errores --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form method="POST" action="{{ route('inventario.store') }}">
                @csrf

                <div class="row g-3">

                    {{-- ✅ CAMBIO: codigo -> cliente --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Cliente</label>
                        <input type="text" name="cliente" class="form-control"
                              value="{{ old('cliente') }}" required>
                    </div>


                    <div class="col-md-4">
                        <label class="form-label fw-bold">Alcaldía</label>
                        <input type="text" name="alcaldia" class="form-control"
                               value="{{ old('alcaldia') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Ubicación</label>
                        <input type="text" name="ubicacion" class="form-control"
                               value="{{ old('ubicacion') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Precio</label>
                        <input type="number" step="0.01" name="precio" class="form-control"
                               value="{{ old('precio') }}" required>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-bold">Estado</label>
                        <select name="estado" class="form-select" required>
                            <option value="disponible" {{ old('estado') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="reservado"  {{ old('estado') == 'reservado'  ? 'selected' : '' }}>En proceso de venta</option>
                            <option value="vendido"    {{ old('estado') == 'vendido'    ? 'selected' : '' }}>Vendido</option>
                        </select>
                    </div>

                </div> {{-- /row --}}

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-warning fw-bold">
                        Guardar Terreno
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

@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Card centrado --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 600px;">
        
        {{-- CAMBIO: Cabecera oscura y h4 para el título --}}
        <div class="card-header bg-dark text-white border-0">
            <h4 class="mb-0">Editar Compra #{{ $compra->id }}</h4>
        </div>

        {{-- CAMBIO: card-body con p-4 --}}
        <div class="card-body p-4">
            <form action="{{ route('compras.update', $compra->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- CAMBIO: Sección añadida --}}
                <h6 class="text-muted">Detalles de la Compra</h6>
                <hr class="mt-1 mb-3 border-secondary">

                {{-- Proveedor --}}
                <div class="mb-3">
                    <label for="proveedor_id" class="form-label">Proveedor</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-truck fa-fw"></i></span>
                        <select class="form-select @error('proveedor_id') is-invalid @enderror" id="proveedor_id" name="proveedor_id" required>
                            <option value="">Seleccione un Proveedor</option>
                            @foreach ($proveedores as $proveedor)
                                <option value="{{ $proveedor->id }}" {{ old('proveedor_id', $compra->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                    {{ $proveedor->nombre }} ({{ $proveedor->empresa }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('proveedor_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                {{-- Total --}}
                <div class="mb-3">
                    <label for="total" class="form-label">Total de la Compra</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0.01" class="form-control @error('total') is-invalid @enderror" id="total" name="total" value="{{ old('total', $compra->total) }}" required>
                    </div>
                    {{-- CAMBIO: Error movido fuera del input-group --}}
                    @error('total') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Método de Pago --}}
                <div class="mb-3">
                    <label for="metodo_pago" class="form-label">Método de Pago</label>
                    {{-- CAMBIO: Input group con ícono --}}
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-credit-card fa-fw"></i></span>
                        <select class="form-select @error('metodo_pago') is-invalid @enderror" id="metodo_pago" name="metodo_pago" required>
                            <option value="">Seleccione...</option>
                            @foreach (['efectivo', 'tarjeta', 'credito', 'transferencia'] as $metodo)
                                <option value="{{ $metodo }}" {{ old('metodo_pago', $compra->metodo_pago) == $metodo ? 'selected' : '' }}>
                                    {{ ucfirst($metodo) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('metodo_pago') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                {{-- Descripción --}}
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción o Concepto (Opcional)</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $compra->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('compras.index') }}" class="btn btn-secondary">
                        {{-- CAMBIO: Ícono añadido --}}
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    
                    @if (Auth::user()->hasPermissionTo('compras', 'editar'))
                        <button type="submit" class="btn btn-success">
                            {{-- CAMBIO: Ícono estandarizado --}}
                            <i class="fas fa-sync me-1"></i> Actualizar Compra
                        </button>
                    @else
                        <span class="text-danger">No tienes permiso para editar este registro.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
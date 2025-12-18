@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">

                <div class="card-header bg-dark text-white border-0">
                    <h4 class="mb-0"><i class="fas fa-edit me-2"></i> Editar Casa: {{ $producto->nombre }}</h4>
                </div>

                <div class="card-body p-4">

                    <form action="{{ route('productos.update', $producto->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">

                            {{-- ============================================= --}}
                            {{-- COLUMNA IZQUIERDA (Información y Stock)     --}}
                            {{-- ============================================= --}}
                            <div class="col-md-7 border-end pe-4">

                                <h6 class="text-muted">Detalles de la Casa</h6>
                                <hr class="mt-1 mb-3 border-secondary">

                                {{-- Campo Nombre --}}
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre de la Casa</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-box fa-fw"></i></span>
                                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $producto->nombre) }}" required>
                                    </div>
                                    @error('nombre')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo Descripción --}}
                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                </div>

                                {{-- Campo Precio --}}
                                <div class="mb-3">
                                    <label for="precio" class="form-label">Precio de Venta</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-dollar-sign fa-fw"></i></span>
                                        <input type="number" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio', $producto->precio) }}" step="0.01" min="0" required>
                                    </div>
                                    @error('precio')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h6 class="text-muted mt-4">Inventario</h6>
                                <hr class="mt-1 mb-3 border-secondary">

                                <div class="row">
                                    {{-- Stock Actual (readonly) --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="stock" class="form-label">Stock Actual</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-boxes fa-fw"></i></span>
                                            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock', $producto->inventario->stock ?? 0) }}" readonly disabled>
                                        </div>
                                        <div class="form-text">Se ajusta automáticamente.</div>
                                    </div>
                                    {{-- Stock Mínimo --}}
                                    <div class="col-md-6 mb-3">
                                        <label for="cantidad_minima" class="form-label">Stock Mínimo (Alerta)</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="fas fa-exclamation-triangle fa-fw"></i></span>
                                            <input type="number" class="form-control @error('cantidad_minima') is-invalid @enderror" id="cantidad_minima" name="cantidad_minima" value="{{ old('cantidad_minima', $producto->inventario->cantidad_minima ?? 5) }}" min="0" required>
                                        </div>
                                        @error('cantidad_minima')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div> {{-- Fin Columna Izquierda --}}


                            {{-- ============================================= --}}
                            {{-- COLUMNA DERECHA (Categoría e Imagen)        --}}
                            {{-- ============================================= --}}
                            <div class="col-md-5 ps-4">

                                <h6 class="text-muted">Clasificación e Imagen</h6>
                                <hr class="mt-1 mb-3 border-secondary">

                                {{-- Campo Categoría --}}
                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Categoría</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tags fa-fw"></i></span>
                                        <select class="form-select @error('categoria_id') is-invalid @enderror" id="categoria_id" name="categoria_id" required>
                                            <option value="" disabled>Seleccione una categoría</option>
                                            @foreach($categorias as $categoria)
                                                <option value="{{ $categoria->id }}" {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                    {{ $categoria->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('categoria_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Campo Imagen --}}
                                <div class="mb-3">
                                    <label for="imagen" class="form-label">Actualizar Imagen (Opcional)</label>
                                    <div class="input-group mb-2">
                                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" id="imagen" name="imagen" accept="image/png,image/jpeg,image/webp">
                                    </div>

                                    {{-- Contenedor de la vista previa --}}
                                    <div id="image-preview-container" class="border rounded p-2 text-center bg-light position-relative">
                                        {{-- Vista previa de la NUEVA imagen --}}
                                        <img id="image-preview" src="#" alt="Vista previa"
                                             class="img-fluid rounded"
                                             style="display: none; max-width: 100%; max-height: 200px; object-fit: contain; margin: 0 auto;"/>

                                        {{-- Placeholder/Imagen Actual --}}
                                        {{-- ESTE DIV AHORA TIENE 'd-flex' POR DEFECTO --}}
                                        <div id="image-current-placeholder" class="d-flex align-items-center justify-content-center" style="height: 200px;">
                                            @if ($producto->imagen)
                                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" style="max-width: 100%; max-height: 200px; object-fit: contain; border-radius: 0.25rem;">
                                            @else
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                            @endif
                                        </div>

                                        {{-- "X" roja para quitar la imagen seleccionada --}}
                                        <button type="button" id="clear-image-btn"
                                                class="position-absolute"
                                                style="display: none; top: 0.5rem; right: 0.75rem; z-index: 10; background: none; border: none; font-size: 2rem; color: #dc3545; line-height: 1; cursor: pointer; padding: 0;"
                                                title="Quitar imagen seleccionada">
                                            &times;
                                        </button>
                                    </div>

                                    @error('imagen')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                            </div> {{-- Fin Columna Derecha --}}

                        </div> {{-- Fin Row Principal --}}


                        <hr>

                        {{-- Botones de acción --}}
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('productos.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-sync me-1"></i> Actualizar Producto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- Script para la vista previa de la imagen (modo EDITAR) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const imageInput = document.getElementById('imagen');
        const imagePreview = document.getElementById('image-preview');
        const imageCurrentPlaceholder = document.getElementById('image-current-placeholder'); // Div de la imagen actual
        const clearImageBtn = document.getElementById('clear-image-btn');
        const imagePreviewContainer = document.getElementById('image-preview-container');

        // Función para actualizar la vista previa
        function updateImagePreview(file) {
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';

                    {{-- ============================================= --}}
                    {{-- CAMBIO AQUÍ: Usamos d-none para ocultar el placeholder --}}
                    {{-- ============================================= --}}
                    imageCurrentPlaceholder.classList.add('d-none');

                    clearImageBtn.style.display = 'block';
                    imagePreviewContainer.style.height = 'auto';
                }
                reader.readAsDataURL(file);
            } else {
                // Si no hay archivo (o se limpió), muestra la imagen actual/placeholder
                imagePreview.src = '#';
                imagePreview.style.display = 'none';

                {{-- ============================================= --}}
                {{-- CAMBIO AQUÍ: Quitamos d-none para mostrar el placeholder --}}
                {{-- ============================================= --}}
                imageCurrentPlaceholder.classList.remove('d-none');

                clearImageBtn.style.display = 'none';
                imagePreviewContainer.style.height = '';
            }
        }

        // Event listener para el input de archivo
        imageInput.addEventListener('change', function(event) {
            updateImagePreview(event.target.files[0]);
        });

        // Event listener para el botón de limpiar
        clearImageBtn.addEventListener('click', function() {
            imageInput.value = '';
            updateImagePreview(null); // Resetea la vista (vuelve a mostrar la imagen actual)
        });
    });
</script>
@endpush

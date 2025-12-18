@extends('layouts.app')

@section('content')
<div class="container">

    {{-- CAMBIO: Barra de Título y Botón Crear (sin card) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Casas</h2>

        {{-- Botón para CREAR PRODUCTO --}}
        @if (Auth::user()->hasPermissionTo('productos', 'alta'))
            <a href="{{ route('productos.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-1"></i> Crear Nuevo Casa
            </a>
        @endif
    </div>

    {{-- CAMBIO: Tabla libre en el contenedor --}}
    <div class="table-responsive">

        {{-- Se mantiene el estilo cebra y cabecera oscura --}}
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nombre</th>
                    <th style="width: 100px;">Imagen</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th style="width: 200px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>
                        <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://placehold.co/100x100/EBF5FB/333333?text=N/A' }}"
                             alt="{{ $producto->nombre }}"
                             class="img-fluid rounded"
                             style="width: 60px; height: 60px; object-fit: cover;">
                    </td>
                    <td>{{ $producto->categoria->nombre ?? 'N/A' }}</td>
                    <td>${{ number_format($producto->precio, 2) }}</td>
                    <td>
                        {{ $producto->inventario->stock ?? '0' }}
                    </td>
                    <td>
                        {{-- Editar --}}
                        @if (Auth::user()->hasPermissionTo('productos', 'editar'))
                            <a href="{{ route('productos.edit', $producto->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                        @endif

                        {{-- Eliminar (con modal) --}}
                        @if (Auth::user()->hasPermissionTo('productos', 'eliminar'))
                            <button type="button" class="btn btn-sm btn-danger"
                                    title="Eliminar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-item-nombre="{{ $producto->nombre }}"
                                    data-form-action="{{ route('productos.destroy', $producto->id) }}">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted p-4">
                        No hay productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


{{-- Modal de Confirmación de Eliminación (Genérico) --}}
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar:
                <br>
                <strong id="modalItemNombre" class="fs-5"></strong>?
                <br><br>
                <small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>

                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Sí, Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


{{-- Script para pasar datos al modal (Genérico) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var confirmDeleteModal = document.getElementById('confirmDeleteModal');

        if (confirmDeleteModal) {
            confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;

                var itemNombre = button.getAttribute('data-item-nombre');
                var formAction = button.getAttribute('data-form-action');

                var modalBodyNombre = confirmDeleteModal.querySelector('#modalItemNombre');
                var deleteForm = confirmDeleteModal.querySelector('#deleteForm');

                modalBodyNombre.textContent = itemNombre;
                deleteForm.setAttribute('action', formAction);
            });
        }
    });
</script>
@endpush

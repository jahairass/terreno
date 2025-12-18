@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- Barra de Título y Botón Crear --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Registro de Compras</h2>
        
        @if (Auth::user()->hasPermissionTo('compras', 'alta'))
            <a href="{{ route('compras.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i> Registrar Nueva Compra
            </a>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th>Descripción</th>
                    <th>Método Pago</th>
                    <th>Total</th>
                    <th style="width: 200px;">Acciones</th> {{-- Ancho ajustado --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($compras as $compra)
                <tr>
                    <td>{{ $compra->id }}</td>
                    <td>{{ $compra->proveedor->nombre ?? 'N/A' }}</td>
                    <td>{{ $compra->created_at->format('Y-m-d') }}</td>
                    <td>{{ $compra->descripcion ?? 'Sin descripción' }}</td>
                    <td><span class="badge bg-secondary">{{ ucfirst($compra->metodo_pago) }}</span></td>
                    <td>${{ number_format($compra->total, 2) }}</td>
                    <td>
                        {{-- Editar --}}
                        @if (Auth::user()->hasPermissionTo('compras', 'editar'))
                            <a href="{{ route('compras.edit', $compra->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                        @endif

                        {{-- Eliminar (con modal) --}}
                        @if (Auth::user()->hasPermissionTo('compras', 'eliminar'))
                            <button type="button" class="btn btn-sm btn-danger" 
                                    title="Eliminar"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal"
                                    {{-- Usamos el ID de la compra para el mensaje --}}
                                    data-item-nombre="la compra #{{ $compra->id }}"
                                    data-form-action="{{ route('compras.destroy', $compra->id) }}">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted p-4">No hay registros de compras.</td>
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


{{-- Scripts para el Modal --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // Script del Modal de Eliminación
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
@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- Barra de Título y Botón Crear --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Proveedores</h2>
        
        @if (Auth::user()->hasPermissionTo('proveedores', 'alta'))
            <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
                <i class="fas fa-truck me-2"></i> Registrar Nuevo Proveedor
            </a>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre Contacto</th>
                    <th>Empresa</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th style="width: 200px;">Acciones</th> {{-- Ancho ajustado para botones --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->id }}</td>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>{{ $proveedor->empresa ?? 'N/A' }}</td>
                    <td>{{ $proveedor->telefono ?? 'N/A' }}</td>
                    <td>{{ $proveedor->correo ?? 'N/A' }}</td>
                    <td>
                        {{-- Editar --}}
                        @if (Auth::user()->hasPermissionTo('proveedores', 'editar'))
                            <a href="{{ route('proveedores.edit', $proveedor->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                        @endif

                        {{-- Eliminar (con modal) --}}
                        @if (Auth::user()->hasPermissionTo('proveedores', 'eliminar'))
                            <button type="button" class="btn btn-sm btn-danger" 
                                    title="Eliminar"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal"
                                    data-item-nombre="{{ $proveedor->nombre }}"
                                    data-form-action="{{ route('proveedores.destroy', $proveedor->id) }}">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">No hay proveedores registrados.</td>
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
                ¿Estás seguro de que deseas eliminar a: 
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
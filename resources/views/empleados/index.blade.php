@extends('layouts.app')

@section('content')
<div class="container">
    
    {{-- CAMBIO: Barra de Título y Botón Crear (sin card) --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Gestión de Empleados</h2>
        
        @if (Auth::user()->hasPermissionTo('usuarios', 'alta'))
            <a href="{{ route('empleados.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus me-1"></i> Crear Nuevo Empleado
            </a>
        @endif
    </div>

    {{-- CAMBIO: Tabla libre en el contenedor --}}
    <div class="table-responsive">
        
        {{-- Tabla con estilo cebra y cabecera oscura --}}
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Cargo</th>
                    <th>Teléfono</th>
                    <th style="width: 200px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{ $user->cargo ? $user->cargo->nombre : 'Sin Cargo' }}
                    </td>
                    <td>
                        {{ $user->empleado ? $user->empleado->telefono : 'N/A' }}
                    </td>
                    <td>
                        {{-- Botones de Acción --}}
                        
                        {{-- Editar --}}
                        @if (Auth::user()->hasPermissionTo('usuarios', 'editar'))
                            <a href="{{ route('empleados.edit', $user->id) }}" class="btn btn-sm btn-warning me-1" title="Editar">
                                <i class="fas fa-edit me-1"></i> Editar
                            </a>
                        @endif
                        
                        {{-- Eliminar (con modal) --}}
                        @if (Auth::user()->hasPermissionTo('usuarios', 'eliminar') && $user->id !== 1 && $user->id !== Auth::id())
                            <button type="button" class="btn btn-sm btn-danger" 
                                    title="Eliminar"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal"
                                    data-item-nombre="{{ $user->name }}"
                                    data-form-action="{{ route('empleados.destroy', $user->id) }}">
                                <i class="fas fa-trash me-1"></i> Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted p-4">
                        No se encontraron empleados registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal de Confirmación de Eliminación --}}
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

{{-- Script para pasar datos al modal --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var confirmDeleteModal = document.getElementById('confirmDeleteModal');
        
        confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var itemNombre = button.getAttribute('data-item-nombre'); 
            var formAction = button.getAttribute('data-form-action');
            
            var modalBodyNombre = confirmDeleteModal.querySelector('#modalItemNombre');
            var deleteForm = confirmDeleteModal.querySelector('#deleteForm');
            
            modalBodyNombre.textContent = itemNombre;
            deleteForm.setAttribute('action', formAction);
        });
    });
</script>
@endpush
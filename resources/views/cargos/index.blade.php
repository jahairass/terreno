@extends('layouts.app')

@section('content')
<div class="container">

    {{-- Barra de título y botón --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 page-title">Gestión de Cargos (Roles)</h2>

        @if (Auth::user()->hasPermissionTo('cargos', 'alta'))
            <a href="{{ route('cargos.create') }}" class="btn btn-create">
                <i class="fas fa-plus-circle me-1"></i> Crear Nuevo Cargo
            </a>
        @endif
    </div>

    {{-- Tabla --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle table-roles">
            <thead>
                <tr>
                    <th style="width: 80px;">ID</th>
                    <th>Nombre del Cargo</th>
                    <th style="width: 350px;" class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($cargos as $cargo)
                <tr>
                    <td>{{ $cargo->id }}</td>
                    <td>{{ $cargo->nombre }}</td>
                    <td class="text-center">

                        {{-- Permisos --}}
                        @if (Auth::user()->hasPermissionTo('cargos', 'editar'))
                            <a href="{{ route('cargos.permisos.index', $cargo->id) }}"
                               class="btn btn-sm btn-permissions me-1"
                               title="Gestionar Permisos">
                                <i class="fas fa-key"></i>
                            </a>

                            {{-- Editar --}}
                            <a href="{{ route('cargos.edit', $cargo->id) }}"
                               class="btn btn-sm btn-edit me-1"
                               title="Editar Cargo">
                                <i class="fas fa-edit"></i>
                            </a>
                        @endif

                        {{-- Eliminar --}}
                        @if (Auth::user()->hasPermissionTo('cargos', 'eliminar') && $cargo->id !== 1)
                            <button type="button"
                                    class="btn btn-sm btn-delete"
                                    title="Eliminar"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteModal"
                                    data-cargo-nombre="{{ $cargo->nombre }}"
                                    data-form-action="{{ route('cargos.destroy', $cargo->id) }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted p-4">
                        No se encontraron cargos registrados.
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

            <div class="modal-header text-white" style="background-color:#03045e;">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el cargo:
                <br>
                <strong id="modalCargoNombre" class="fs-5"></strong>?
                <br><br>
                <small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancelar
                </button>

                <form id="deleteForm" method="POST">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('confirmDeleteModal');

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const nombre = button.getAttribute('data-cargo-nombre');
        const action = button.getAttribute('data-form-action');

        modal.querySelector('#modalCargoNombre').textContent = nombre;
        modal.querySelector('#deleteForm').setAttribute('action', action);
    });
});
</script>
@endpush

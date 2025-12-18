@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">
        Gestión de Permisos para el Cargo: <span class="badge bg-primary">{{ $cargo->nombre }}</span>
    </h2>

    <div class="alert alert-info d-flex justify-content-between align-items-center">
        <span>Define qué acciones puede realizar este cargo en cada módulo.</span>
        {{-- NUEVO: Botón único para Seleccionar/Deseleccionar Todos --}}
        <button type="button" class="btn btn-sm btn-outline-primary" id="toggle-all-permissions">
            <i class="fas fa-tasks me-1"></i> Seleccionar/Deseleccionar Todos
        </button>
    </div>

    {{-- Mensajes de Sesión --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning">{{ session('warning') }}</div>
    @endif

    {{-- Formulario que enviará toda la matriz al PermisoController@update --}}
    <form action="{{ route('cargos.permisos.update', $cargo->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle" id="permissions-table">
                <thead class="table-dark sticky-top">
                    <tr>
                        <th style="width: 25%;">Módulo</th>
                        <th class="text-center">Mostrar</th>
                        <th class="text-center">Detalle</th>
                        <th class="text-center">Alta</th>
                        <th class="text-center">Editar</th>
                        <th class="text-center">Eliminar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modulos as $modulo)
                        @php
                            $permiso = $permisosActuales->get($modulo->id);
                        @endphp
                        <tr>
                            <td class="fw-bold">{{ ucfirst($modulo->nombre) }}</td>
                            @foreach (['mostrar', 'detalle', 'alta', 'editar', 'eliminar'] as $accion)
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input 
                                            class="form-check-input permission-checkbox" 
                                            type="checkbox" 
                                            name="{{ $accion }}[{{ $modulo->id }}]" 
                                            value="1" 
                                            id="check-{{ $modulo->id }}-{{ $accion }}"
                                            {{ ($permiso && $permiso->$accion) ? 'checked' : '' }}
                                        >
                                        <label class="form-check-label visually-hidden" for="check-{{ $modulo->id }}-{{ $accion }}">{{ ucfirst($accion) }}</label>
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4 pb-5 d-flex justify-content-between">
            <a href="{{ route('cargos.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver a Cargos
            </a>
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-save"></i> Guardar Permisos
            </button>
        </div>
    </form>
</div>

{{-- Script para el botón "Seleccionar/Deseleccionar Todos" --}}
<script>
    document.getElementById('toggle-all-permissions').addEventListener('click', function() {
        const checkboxes = document.querySelectorAll('#permissions-table .permission-checkbox');
        
        // Determinar si todos están marcados actualmente
        let allChecked = true;
        checkboxes.forEach(function(checkbox) {
            if (!checkbox.checked) {
                allChecked = false;
            }
        });

        // La nueva acción será lo contrario del estado actual
        const newState = !allChecked; 

        // Aplicar el nuevo estado a todos los checkboxes
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = newState;
        });
    });
</script>
@endsection


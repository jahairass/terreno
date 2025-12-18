@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Card centrado. Aumenté el ancho a 700px para este formulario más largo --}}
    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        
        {{-- ================================================= --}}
        {{-- CAMBIO: Card Header oscuro (bg-dark text-white) --}}
        {{-- ================================================= --}}
        <div class="card-header bg-dark text-white border-0">
            <h4 class="mb-0">Editar Empleado: {{ $empleado->name }}</h4>
        </div>

        {{-- Se añade padding p-4 --}}
        <div class="card-body p-4">

            <form action="{{ route('empleados.update', $empleado->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- SECCIÓN DATOS DE ACCESO (Tabla users) --}}
                <h6 class="text-muted">Datos de Acceso</h6>
                {{-- CAMBIO: HR con borde secundario --}}
                <hr class="mt-1 mb-3 border-secondary">

                <div class="mb-3">
                    <label for="name" class="form-label">Nombre Completo</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user fa-fw"></i></span>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $empleado->name) }}" required>
                    </div>
                    @error('name') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope fa-fw"></i></span>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $empleado->email) }}" required>
                    </div>
                    @error('email') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="cargo_id" class="form-label">Cargo (Rol)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-tag fa-fw"></i></span>
                        <select class="form-select @error('cargo_id') is-invalid @enderror" id="cargo_id" name="cargo_id" required>
                            <option value="">Seleccione un Cargo</option>
                            @foreach ($cargos as $cargo)
                                <option value="{{ $cargo->id }}" {{ old('cargo_id', $empleado->cargo_id) == $cargo->id ? 'selected' : '' }}>
                                    {{ $cargo->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('cargo_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña (Opcional)</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" aria-describedby="passwordHelp">
                    </div>
                    <div id="passwordHelp" class="form-text">Dejar en blanco para no cambiar la contraseña.</div>
                    @error('password') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock fa-fw"></i></span>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>
                
                {{-- SECCIÓN INFORMACIÓN ADICIONAL --}}
                <h6 class="text-muted mt-4">Información Adicional</h6>
                {{-- CAMBIO: HR con borde secundario --}}
                <hr class="mt-1 mb-3 border-secondary">

                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-phone fa-fw"></i></span>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $empleado->empleado->telefono ?? '') }}">
                    </div>
                    @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-map-marker-alt fa-fw"></i></span>
                        <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $empleado->empleado->direccion ?? '') }}">
                    </div>
                    @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                </div>
                
                {{-- Botones de Acción --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('empleados.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Cancelar
                    </a>
                    
                    @if (Auth::user()->hasPermissionTo('usuarios', 'editar'))
                        <button type="submit" class="btn btn-success">
                             <i class="fas fa-sync me-1"></i> Actualizar Empleado
                        </button>
                    @else
                        <span class="text-danger">No tienes permiso para editar.</span>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
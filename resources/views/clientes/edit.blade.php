@extends('layouts.app')

@section('content')
<div class="container-fluid py-4" style="background:#ffffff;min-height:100vh;">

    {{-- ENCABEZADO --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-4"
         style="
            background:linear-gradient(135deg,#021a3a,#020f26);
            border-radius:18px;
            box-shadow:0 20px 45px rgba(2,15,38,.55);
         ">
        <div>
            <div class="text-uppercase"
                 style="letter-spacing:.18em;font-size:.75rem;color:#93c5fd;">
                Inmobiliaria • Clientes
            </div>
            <h3 class="fw-bold mb-0" style="color:#ffffff;">
                Editar cliente: {{ $cliente->Nombre }}
            </h3>
        </div>

        <a href="{{ route('clientes.index') }}"
           class="btn"
           style="
                border:1px solid rgba(255,255,255,.45);
                color:#ffffff;
                border-radius:14px;
           ">
            ← Volver
        </a>
    </div>

    {{-- CARD (AZUL ULTRA INTENSO) --}}
    <div class="card border-0 shadow-sm"
         style="
            border-radius:22px;
            background:linear-gradient(180deg,#07162e,#020f26);
            border:1px solid rgba(255,255,255,.22);
            color:#f8fafc;
            box-shadow:0 25px 60px rgba(2,15,38,.6);
         ">
        <div class="card-body p-4 p-md-5">

            {{-- ERRORES --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0"
                     style="border-radius:14px;">
                    <div class="fw-semibold mb-1">Revisa el formulario</div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- NOMBRE --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold" style="color:#f8fafc;">
                        Nombre del cliente
                    </label>
                    <div class="input-group">
                        <span class="input-group-text"
                              style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text"
                               name="nombre"
                               class="form-control"
                               value="{{ old('nombre', $cliente->Nombre) }}"
                               required
                               style="background:#ffffff;border:1px solid #1d4ed8;">
                    </div>
                </div>

                <div class="row g-3">
                    {{-- TELÉFONO --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:#f8fafc;">Teléfono</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                                <i class="fas fa-phone"></i>
                            </span>
                            <input type="text"
                                   name="telefono"
                                   class="form-control"
                                   value="{{ old('telefono', $cliente->telefono) }}"
                                   style="background:#ffffff;border:1px solid #1d4ed8;">
                        </div>
                    </div>

                    {{-- CORREO --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:#f8fafc;">Correo</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email"
                                   name="correo"
                                   class="form-control"
                                   value="{{ old('correo', $cliente->correo) }}"
                                   style="background:#ffffff;border:1px solid #1d4ed8;">
                        </div>
                    </div>
                </div>

                <div class="row g-3 mt-2">
                    {{-- IDENTIFICACIÓN --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:#f8fafc;">Identificación</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                                <i class="fas fa-id-card"></i>
                            </span>
                            <input type="text"
                                   name="identificacion"
                                   class="form-control"
                                   value="{{ old('identificacion', $cliente->identificacion) }}"
                                   style="background:#ffffff;border:1px solid #1d4ed8;">
                        </div>
                    </div>

                    {{-- FECHA --}}
                    <div class="col-md-6">
                        <label class="form-label fw-semibold" style="color:#f8fafc;">Fecha (opcional)</label>
                        <div class="input-group">
                            <span class="input-group-text"
                                  style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                                <i class="fas fa-calendar"></i>
                            </span>
                            <input type="date"
                                   name="fecha_compra"
                                   class="form-control"
                                   value="{{ old('fecha_compra', $cliente->fecha_compra) }}"
                                   style="background:#ffffff;border:1px solid #1d4ed8;">
                        </div>
                    </div>
                </div>

                {{-- DIRECCIÓN --}}
                <div class="mt-3">
                    <label class="form-label fw-semibold" style="color:#f8fafc;">Dirección</label>
                    <div class="input-group">
                        <span class="input-group-text"
                              style="background:#020f26;border:1px solid rgba(255,255,255,.35);color:#38bdf8;">
                            <i class="fas fa-location-dot"></i>
                        </span>
                        <input type="text"
                               name="direccion"
                               class="form-control"
                               value="{{ old('direccion', $cliente->direccion) }}"
                               style="background:#ffffff;border:1px solid #1d4ed8;">
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="d-flex justify-content-between mt-5">
                    <a href="{{ route('clientes.index') }}"
                       class="btn"
                       style="border:1px solid rgba(255,255,255,.55);color:#ffffff;border-radius:14px;">
                        ✖ Cancelar
                    </a>

                    <button type="submit"
                            class="btn"
                            style="background:#1d4ed8;color:#ffffff;border-radius:14px;font-weight:800;">
                        💾 Guardar cambios
                    </button>
                </div>

            </form>
        </div>
    </div>

</div>
@endsection

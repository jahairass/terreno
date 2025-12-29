@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Encabezado estilo inmobiliaria --}}
    <div class="d-flex justify-content-between align-items-center mb-4 p-3"
         style="background:linear-gradient(135deg,#0a2540,#0f3d63);border:1px solid rgba(255,255,255,.10);border-radius:14px;">
        <div>
            <div class="text-uppercase" style="letter-spacing:.18em;font-size:.75rem;color:#93c5fd;">
                Inmobiliaria • Clientes
            </div>
            <h3 class="mb-0 fw-bold" style="color:#ffffff;">
                Registrar nuevo cliente
            </h3>
        </div>

        <a href="{{ route('clientes.index') }}"
           class="btn btn-outline-light"
           style="border-radius:12px;border-color:rgba(255,255,255,.22);">
            <i class="fas fa-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- Contenedor --}}
    <div class="row g-4">
        {{-- Formulario --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm"
                 style="border-radius:18px;background:#0b1220;color:#e6edf7;border:1px solid rgba(255,255,255,.10);">
                <div class="card-body p-4 p-md-5">

                    {{-- Mensaje general de errores --}}
                    @if ($errors->any())
                        <div class="alert alert-danger border-0"
                             style="background:rgba(220,53,69,.12);color:#ffd4d9;border-radius:14px;">
                            <div class="fw-semibold mb-1"><i class="fas fa-triangle-exclamation me-1"></i> Revisa el formulario</div>
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('clientes.store') }}" method="POST">
                        @csrf

                        {{-- Nombre --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold" style="color:#cbd5e1;">Nombre del cliente</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    name="nombre"
                                    value="{{ old('nombre') }}"
                                    placeholder="Ej. Juan Pérez"
                                    required
                                    style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                >
                            </div>
                            @error('nombre') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="row g-3">
                            {{-- Teléfono --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color:#cbd5e1;">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('telefono') is-invalid @enderror"
                                        name="telefono"
                                        value="{{ old('telefono') }}"
                                        placeholder="Ej. 55 1234 5678"
                                        style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                    >
                                </div>
                                @error('telefono') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Correo --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color:#cbd5e1;">Correo</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input
                                        type="email"
                                        class="form-control @error('correo') is-invalid @enderror"
                                        name="correo"
                                        value="{{ old('correo') }}"
                                        placeholder="Ej. cliente@correo.com"
                                        style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                    >
                                </div>
                                @error('correo') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            {{-- Identificación --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color:#cbd5e1;">Identificación</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                        <i class="fas fa-id-card"></i>
                                    </span>
                                    <input
                                        type="text"
                                        class="form-control @error('identificacion') is-invalid @enderror"
                                        name="identificacion"
                                        value="{{ old('identificacion') }}"
                                        placeholder="INE / RFC / CURP"
                                        style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                    >
                                </div>
                                @error('identificacion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>

                            {{-- Fecha de compra --}}
                            <div class="col-md-6">
                                <label class="form-label fw-semibold" style="color:#cbd5e1;">Fecha (opcional)</label>
                                <div class="input-group">
                                    <span class="input-group-text"
                                          style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input
                                        type="date"
                                        class="form-control @error('fecha_compra') is-invalid @enderror"
                                        name="fecha_compra"
                                        value="{{ old('fecha_compra') }}"
                                        style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                    >
                                </div>
                                @error('fecha_compra') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="mt-3">
                            <label class="form-label fw-semibold" style="color:#cbd5e1;">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"
                                      style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#38bdf8;border-radius:12px 0 0 12px;">
                                    <i class="fas fa-location-dot"></i>
                                </span>
                                <input
                                    type="text"
                                    class="form-control @error('direccion') is-invalid @enderror"
                                    name="direccion"
                                    value="{{ old('direccion') }}"
                                    placeholder="Calle, colonia, municipio, estado"
                                    style="background:#07101e;border:1px solid rgba(255,255,255,.12);color:#e6edf7;border-radius:0 12px 12px 0;"
                                >
                            </div>
                            @error('direccion') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        {{-- Botones --}}
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('clientes.index') }}"
                               class="btn btn-outline-light"
                               style="border-radius:12px;border-color:rgba(255,255,255,.22);">
                                <i class="fas fa-xmark me-1"></i> Cancelar
                            </a>

                            @if (Auth::user()->hasPermissionTo('clientes', 'alta'))
                                <button type="submit"
                                        class="btn"
                                        style="background:#2563eb;color:#ffffff;border-radius:12px;font-weight:800;">
                                    <i class="fas fa-floppy-disk me-1"></i> Guardar cliente
                                </button>
                            @else
                                <span class="text-danger">No tienes permiso para registrar clientes.</span>
                            @endif
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- Panel lateral (info) --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100"
                 style="border-radius:18px;background:linear-gradient(180deg,#0a2540,#0b1220);color:#e6edf7;border:1px solid rgba(255,255,255,.10);">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center mb-3">
                        <div class="me-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;border-radius:14px;background:rgba(56,189,248,.18);color:#38bdf8;">
                            <i class="fas fa-map-location-dot"></i>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size:1.05rem;">CRM de Terrenos</div>
                            <div style="color:#93c5fd;font-size:.9rem;">Captura rápida y ordenada</div>
                        </div>
                    </div>

                    <hr style="border-color:rgba(255,255,255,.12);">

                    <div class="mb-3" style="color:#cbd5e1;">
                        <div class="fw-semibold mb-1">
                            <i class="fas fa-circle-check me-1" style="color:#38bdf8;"></i> Tip
                        </div>
                        <div style="color:#93c5fd;">
                            Guarda el nombre tal como aparece en la identificación. Esto mejora reportes, contratos y ventas.
                        </div>
                    </div>

                    <div class="p-3"
                         style="border-radius:16px;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.10);">
                        <div class="fw-semibold mb-2" style="color:#38bdf8;">
                            Campos recomendados
                        </div>
                        <ul class="mb-0" style="color:#cbd5e1;">
                            <li>Nombre completo</li>
                            <li>Teléfono con lada</li>
                            <li>Correo válido</li>
                            <li>Dirección (opcional)</li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</div>
@endsection

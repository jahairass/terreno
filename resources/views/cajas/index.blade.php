@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestión de Flujo de Caja</h2>

    {{-- Errores de validación --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Error de Validación:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ========================================================== --}}
    {{-- CASO 1: CAJA CERRADA (Mostrar Formulario de Apertura) --}}
    {{-- ========================================================== --}}
    @if (!$cajaAbierta)
        <div class="card shadow-lg mx-auto" style="max-width: 500px;">
            <div class="card-header bg-success text-white">
                <h4 class="mb-0"><i class="fas fa-money-check-alt me-2"></i> Abrir Caja del Día</h4>
            </div>
            <div class="card-body">
                <p>No tienes una caja abierta. Registra el saldo inicial para empezar las ventas.</p>
                
                @if (Auth::user()->hasPermissionTo('cajas', 'alta'))
                    <form action="{{ route('cajas.abrir') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="saldo_inicial" class="form-label">Saldo Inicial (Fondo de Caja)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" step="0.01" min="0" class="form-control @error('saldo_inicial') is-invalid @enderror" 
                                       id="saldo_inicial" name="saldo_inicial" value="{{ old('saldo_inicial', 0.00) }}" required>
                                @error('saldo_inicial') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="fas fa-box-open me-2"></i> Abrir Caja
                        </button>
                    </form>
                @else
                    <div class="alert alert-warning">No tienes permiso para abrir una caja.</div>
                @endif
            </div>
        </div>

    {{-- ========================================================== --}}
    {{-- CASO 2: CAJA ABIERTA (Mostrar Estado y Movimientos) --}}
    {{-- ========================================================== --}}
    @else
        <div class="row">
            {{-- Panel de Información General y Cierre --}}
            <div class="col-md-5 mb-4">
                <div class="card shadow-lg h-100 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-cash-register me-2"></i> Caja Abierta (Turno)</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>ID Caja:</strong> {{ $cajaAbierta->id }}</p>
                        <p class="mb-1"><strong>Cajero:</strong> {{ $cajaAbierta->user->name ?? 'N/A' }}</p>
                        <p class="mb-1"><strong>Apertura:</strong> {{ $cajaAbierta->fecha_hora_apertura->format('d/m/Y H:i') }}</p>
                        <hr> 
                        <h5 class="mt-3">Resumen del Turno Actual</h5>
                        <p class="d-flex justify-content-between mb-1">
                            <span>Saldo Inicial:</span> 
                            <span class="badge bg-secondary fs-6">${{ number_format($cajaAbierta->saldo_inicial, 2) }}</span>
                        </p>
                        
                        <p class="d-flex justify-content-between mb-1 text-success">
                            <span>+ Ventas en Efectivo:</span> 
                            <span class="fw-bold">${{ number_format($ventasEfectivo ?? 0, 2) }}</span>
                        </p>

                        @php 
                            // Esta variable $saldoMovimientos la calcula tu controlador
                            $saldoMovimientos = $movimientos->sum(function($m){ return $m->tipo === 'ingreso' ? $m->monto : -$m->monto; });
                        @endphp
                        <p class="d-flex justify-content-between mb-1 {{ $saldoMovimientos >= 0 ? 'text-info' : 'text-danger' }}">
                           <span>+/- Movimientos Manuales:</span>
                           <span class="fw-bold">
                                {{ $saldoMovimientos >= 0 ? '+' : '-' }}${{ number_format(abs($saldoMovimientos), 2) }}
                           </span>
                        </p>
                        
                        <hr>
                        <h5 class="mt-3">Saldo Actual Estimado:</h5>
                        <div class="display-5 fw-bold text-primary">${{ number_format($saldoActual, 2) }}</div> 
                        <small class="text-muted">(Saldo Inicial + Ventas Efectivo +/- Mov. Manuales)</small> 
                    </div>
                    
                    {{-- ***** MODIFICADO: Añadido botón de Movimiento ***** --}}
                    <div class="card-footer d-flex justify-content-between p-3">
                        
                        {{-- 1. Botón para abrir el Modal --}}
                        @if (Auth::user()->hasPermissionTo('cajas', 'editar')) {{-- O el permiso que definas --}}
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#movimientoModal">
                                <i class="fas fa-exchange-alt me-2"></i> Registrar Movimiento
                            </button>
                        @endif

                        {{-- 2. Botón de Cierre --}}
                        @if (Auth::user()->hasPermissionTo('cajas', 'eliminar'))
                            <form action="{{ route('cajas.cerrar') }}" method="POST" onsubmit="return confirm('¿Estás seguro de cerrar la caja? Se registrará un movimiento con el total de ventas en efectivo.');">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-lock me-2"></i> Cerrar Caja
                                </button>
                            </form>
                        @else
                            <div class="alert alert-warning text-center py-2 mb-0">No tienes permiso para cerrar la caja.</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Historial de Movimientos (Tu código, sin cambios) --}}
            <div class="col-md-7 mb-4">
                <div class="card shadow-lg h-100">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-exchange-alt me-2"></i> Movimientos Registrados en Caja</h4>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 450px; overflow-y: auto;">
                               @if ($movimientos->isEmpty() && ($ventasEfectivo ?? 0) == 0 && !$cajaAbierta && !isset($cajaCerradaReciente)) 
                                   <div class="alert alert-light text-center m-3">Aún no hay movimientos registrados.</div>
                               @else
                                <ul class="list-group list-group-flush">
                                    {{-- Mostrar movimientos manuales --}}
                                    @forelse ($movimientos as $movimiento)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div class="flex-grow-1">
                                                <i class="fas {{ $movimiento->tipo === 'ingreso' ? 'fa-arrow-circle-up text-success' : 'fa-arrow-circle-down text-danger' }} me-2"></i>
                                                {{ $movimiento->descripcion }} 
                                                <small class="text-muted d-block">
                                                    {{ $movimiento->created_at->format('H:i') }} - 
                                                    {{ ucfirst($movimiento->metodo_pago) }}
                                                    {{-- ¡NUEVO! Mostrar quién lo registró --}}
                                                    @if($movimiento->user)
                                                        - ({{ $movimiento->user->name }})
                                                    @endif
                                                </small>
                                            </div>
                                            <span class="fw-bold fs-6 {{ $movimiento->tipo === 'ingreso' ? 'text-success' : 'text-danger' }}">
                                                {{ $movimiento->tipo === 'egreso' ? '-' : '+' }}${{ number_format($movimiento->monto, 2) }}
                                            </span>
                                        </li>
                                    @empty
                                        @if($cajaAbierta)
                                            <li class="list-group-item text-center text-muted">No hay movimientos manuales registrados en este turno.</li>
                                        @endif
                                    @endforelse
                                    
                                    @if($cajaAbierta && $movimientos->isEmpty() && ($ventasEfectivo ?? 0) > 0)
                                        <li class="list-group-item text-center text-muted">Solo se han registrado ventas en efectivo.</li>
                                    @endif
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>


{{-- ========================================================== --}}
{{-- ¡NUEVO! Modal para Registrar Movimiento --}}
{{-- ========================================================== --}}
@if ($cajaAbierta)
<div class="modal fade" id="movimientoModal" tabindex="-1" aria-labelledby="movimientoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('cajas.movimiento') }}" method="POST">
                @csrf
                <input type="hidden" name="caja_id" value="{{ $cajaAbierta->id }}">
                
                <div class="modal-header">
                    <h5 class="modal-title" id="movimientoModalLabel">Registrar Movimiento Manual</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    
                    {{-- Tipo de Movimiento --}}
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Movimiento</label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="egreso">Salida de Efectivo (Gasto, Retiro)</option>
                            <option value="ingreso">Entrada de Efectivo (Ingreso, Fondo)</option>
                        </select>
                    </div>

                    {{-- Monto (Siempre positivo) --}}
                    <div class="mb-3">
                        <label for="monto" class="form-label">Monto</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0.01" class="form-control" id="monto" name="monto" required placeholder="0.00">
                        </div>
                    </div>

                    {{-- Descripción (Motivo) --}}
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción (Motivo)</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ej: Pago a proveedor de agua, Añadir cambio..." required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Movimiento</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection
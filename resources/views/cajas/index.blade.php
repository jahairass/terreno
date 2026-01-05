@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 980px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0 fw-bold">Cobros</h2>
    </div>

    {{-- Mensajes success/error --}}
    @if (session('success'))
        <div class="alert alert-success">
            <strong>Éxito:</strong> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            <strong>Error:</strong> {{ session('error') }}
        </div>
    @endif

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

    <div class="row g-3">
        {{-- ============================= --}}
        {{-- PANEL PRINCIPAL DE COBRO --}}
        {{-- ============================= --}}
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background:#198754;">
                    <div class="fw-bold">Cobrar a Cliente</div>
                    <small></small>
                </div>

                <form action="{{ route('cajas.cobro') }}" method="POST">
                    @csrf

                    <div class="card-body">
                        {{-- Cliente --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cliente</label>
                            <select name="cliente_id" class="form-select" id="cliente_id" required>
                                <option value="">-- Selecciona --</option>
                                @foreach($clientes as $c)
                                    <option value="{{ $c->idCli }}"
                                        data-tel="{{ $c->telefono }}"
                                        data-dir="{{ $c->direccion }}">
                                        {{ $c->Nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Al seleccionar se muestra teléfono y dirección.</small>
                        </div>

                        {{-- Info del cliente (auto) --}}
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="tel" placeholder="Teléfono" disabled>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="dir" placeholder="Dirección" disabled>
                            </div>
                        </div>

                        {{-- Caja tipo POS (Total / Resta) --}}
                        <div class="border rounded p-3 mb-3" style="background:#f8f9fa;">
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total del Cobro:</span>
                                <span class="fw-bold" id="lblSubtotal">$0.00</span>
                            </div>

                            <div class="d-flex justify-content-between mt-1">
                                <span class="text-danger fw-bold">Resta por Pagar:</span>
                                <span class="text-danger fw-bold fs-3" id="lblTotal">$0.00</span>
                            </div>

                            <div class="small text-muted mt-2" id="lblMultaInfo">Multa: $0.00 (10% si paga tarde)</div>
                        </div>

                        {{-- Mensualidad + mensualidades --}}
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Pago Mensualidad</label>
                                <select name="mensualidad" class="form-select" id="mensualidad" required>
                                    <option value="4000">4000</option>
                                    <option value="5000">5000</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mensualidades a pagar</label>
                                <input type="number" name="mensualidades" class="form-control" id="mensualidades" min="1" value="1" required>
                                <small class="text-muted">Si adelanta o liquida, aumenta este número.</small>
                            </div>
                        </div>

                        {{-- Fechas (para multa) --}}
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Vencimiento</label>
                                <input type="date" name="fecha_vencimiento" class="form-control" id="fecha_vencimiento" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de Pago</label>
                                <input type="date" name="fecha_pago" class="form-control" id="fecha_pago" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        {{-- Tipo + método --}}
                        <div class="row g-2 mt-1">
                            <div class="col-md-6">
                                <label class="form-label">Tipo de Cobro</label>
                                <select name="tipo" class="form-select" required>
                                    <option value="normal">Normal</option>
                                    <option value="adelanto">Adelanto</option>
                                    <option value="liquidacion">Liquidación</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">¿Cómo paga el cliente?</label>
                                <select name="metodo_pago" class="form-select" required>
                                    <option value="efectivo">Efectivo</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                            </div>
                        </div>

                        {{-- Notas --}}
                        <div class="mt-2">
                            <label class="form-label">Notas (opcional)</label>
                            <input type="text" name="notas" class="form-control" placeholder="Ej: Pago atrasado, convenio...">
                        </div>

                        {{-- Checkbox ticket visual (aún no genera PDF) --}}
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="1" id="imprimir" checked>
                            <label class="form-check-label" for="imprimir">
                                Imprimir Ticket de Entrega
                            </label>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check me-1"></i> Cobrar 
                        </button>
                    </div>

                </form>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- HISTORIAL DE COBROS / MOVIMIENTOS --}}
        {{-- ============================= --}}
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header text-white" style="background:#212529;">
                    <div class="fw-bold">Últimos Cobros / Movimientos</div>
                    <small>Se muestran los últimos 30 registros</small>
                </div>

                <div class="card-body p-0" style="max-height: 610px; overflow:auto;">
                    @if(isset($movimientos) && $movimientos->count())
                        <ul class="list-group list-group-flush">
                            @foreach($movimientos as $m)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div class="fw-bold">
                                            {{ $m->descripcion }}
                                        </div>
                                        <div class="{{ $m->tipo === 'ingreso' ? 'text-success' : 'text-danger' }} fw-bold">
                                            {{ $m->tipo === 'egreso' ? '-' : '+' }}${{ number_format($m->monto, 2) }}
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        {{ $m->created_at->format('d/m/Y H:i') }}
                                        · {{ $m->metodo_pago }}
                                        @if($m->user) · {{ $m->user->name }} @endif
                                    </small>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="p-3 text-muted text-center">Aún no hay cobros registrados.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ✅ JS: autollenar tel/dir + calcular total y multa --}}
<script>
(function(){
    const clienteSel = document.getElementById('cliente_id');
    const tel = document.getElementById('tel');
    const dir = document.getElementById('dir');

    const mensualidad = document.getElementById('mensualidad');
    const mensualidades = document.getElementById('mensualidades');
    const venc = document.getElementById('fecha_vencimiento');
    const pago = document.getElementById('fecha_pago');

    const lblSubtotal = document.getElementById('lblSubtotal');
    const lblTotal = document.getElementById('lblTotal');
    const lblMultaInfo = document.getElementById('lblMultaInfo');

    function money(n){ return '$' + (Number(n||0).toFixed(2)); }

    function setCliente(){
        const opt = clienteSel.options[clienteSel.selectedIndex];
        tel.value = opt?.dataset?.tel || '';
        dir.value = opt?.dataset?.dir || '';
    }

    function calc(){
        const m = Number(mensualidad.value || 0);
        const n = Number(mensualidades.value || 1);
        const subtotal = m * n;

        let multa = 0;

        if(venc.value && pago.value){
            const dv = new Date(venc.value + 'T00:00:00');
            const dp = new Date(pago.value + 'T00:00:00');
            if(dp > dv){
                multa = subtotal * 0.10;
            }
        }

        const total = subtotal + multa;

        lblSubtotal.textContent = money(subtotal);
        lblTotal.textContent = money(total);
        lblMultaInfo.textContent = 'Multa: ' + money(multa) + ' (10% si paga tarde)';
    }

    clienteSel.addEventListener('change', setCliente);

    ['change','keyup','input'].forEach(ev=>{
        mensualidad.addEventListener(ev, calc);
        mensualidades.addEventListener(ev, calc);
        venc.addEventListener(ev, calc);
        pago.addEventListener(ev, calc);
    });

    setCliente();
    calc();
})();
</script>
@endsection

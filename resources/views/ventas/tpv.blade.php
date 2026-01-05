@extends('layouts.app')

@section('content')
<div class="container-fluid" style="height: calc(100vh - 76px);"> 
    
    @if ($cajaAbierta) 
        <div class="row h-100">
            <!-- 1. Columna de Productos -->
            <div class="col-lg-8 d-flex flex-column h-100">
                <h4 class="mb-3 text-primary"><i class="fas fa-bread-slice me-2"></i> Productos Disponibles</h4>

                {{-- ✅ NUEVO PANEL: Pagos mensuales + Estado + Contrato PDF --}}
                <div class="card mb-3 shadow-sm border-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="mb-0 fw-bold text-dark">
                                    <i class="fas fa-file-contract me-2"></i> Financiamiento del Terreno
                                </h6>
                                <small class="text-muted">Vista previa de mensualidades y acceso al contrato PDF</small>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" id="btn-toggle-pagos">
                                    <i class="fas fa-calendar-alt me-1"></i> Ver pagos
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" id="btn-contrato" disabled>
                                    <i class="fas fa-file-pdf me-1"></i> Contrato PDF
                                </button>
                            </div>
                        </div>

                        <div id="panel-pagos" class="mt-3" style="display:none;">
                            <div class="alert alert-info py-2 mb-2">
                                Selecciona <b>Terreno</b> y <b>Mensualidades</b> para ver el calendario.  
                                <span class="text-muted">(Se guardará automáticamente al confirmar la venta)</span>
                            </div>

                            <div class="row g-2 mb-2">
                                <div class="col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted">Total terreno</small>
                                        <div class="fw-bold" id="preview-total-terreno">$0.00</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted">Pago inicial</small>
                                        <div class="fw-bold" id="preview-pago-inicial">$0.00</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="border rounded p-2 bg-light">
                                        <small class="text-muted">Monto mensual</small>
                                        <div class="fw-bold text-success" id="preview-monto-mensual">$0.00</div>
                                    </div>
                                </div>
                            </div>

                            <div id="tabla-pagos-preview"></div>
                        </div>
                    </div>
                </div>
                {{-- ✅ FIN PANEL --}}

                <div class="input-group mb-3 shadow-sm">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                    <input type="search" id="product-search" class="form-control" placeholder="Buscar producto por nombre...">
                </div>

                <div class="d-flex mb-3 overflow-auto pb-2 border-bottom">
                    <button class="btn btn-sm btn-outline-dark me-2 active category-filter" data-category-id="all">Todas</button>
                    @foreach ($categorias as $cat)
                        <button class="btn btn-sm btn-outline-secondary me-2 category-filter" data-category-id="{{ $cat->id }}">{{ $cat->nombre }}</button>
                    @endforeach
                </div>
                
                {{-- Contenedor de productos --}}
                <div class="row g-3 overflow-auto p-2 border rounded shadow-sm bg-white" id="product-list" style="max-height: calc(100vh - 220px);">
                    @forelse ($productos as $producto)
                        <div class="col-4 col-sm-3 col-md-2 product-item" data-category-id="{{ $producto->categoria_id }}">
                            <div class="card h-100 product-card shadow-sm border-0" 
                                 style="cursor: pointer;"
                                 data-id="{{ $producto->id }}" 
                                 data-name="{{ $producto->nombre }}" 
                                 data-price="{{ $producto->precio }}"
                                 data-stock="{{ $producto->inventario->stock ?? 0 }}"
                                 data-image="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://placehold.co/100x100/EBF5FB/333333?text=Sin+Imagen' }}">
                                
                                <div style="height: 100px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #f8f9fa;">
                                    <img src="{{ $producto->imagen ? asset('storage/' . $producto->imagen) : 'https://placehold.co/100x100/EBF5FB/333333?text=Sin+Imagen' }}" 
                                         class="card-img-top" 
                                         alt="{{ $producto->nombre }}"
                                         style="max-height: 100%; width: auto; object-fit: cover;">
                                </div>

                                <div class="card-body p-2 text-center d-flex flex-column justify-content-between">
                                    <h6 class="card-title mb-1 fw-bold fs-sm product-name">{{ $producto->nombre }}</h6> 
                                    <div>
                                        <p class="card-text text-success fs-5 mb-0">${{ number_format($producto->precio, 2) }}</p>
                                        <small class="text-muted product-stock">Stock: {{ $producto->inventario->stock ?? 0 }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-warning w-100 text-center">No hay productos con stock disponible para la venta.</div>
                    @endforelse
                </div>
            </div>

            <!-- 2. Columna del Carrito y Pago -->
            <div class="col-lg-4 d-flex flex-column border-start ps-4 h-100">
                <h4 class="mb-3 text-danger"><i class="fas fa-shopping-cart me-2"></i> Orden Actual</h4>
                
                {{-- Cliente Editable con Dropdown --}}
                <div class="alert alert-info py-2 mb-3">
                    <div><small>Cajero: <strong>{{ Auth::user()->name }}</strong></small></div>
                    <hr class="my-1">
                    <div class="d-flex align-items-center">
                        <small class="me-2">Cliente:</small> 
                        <div class="input-group input-group-sm flex-grow-1">
                            <input type="text" id="temporal-client-name" class="form-control" placeholder="Público General">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Seleccionar Cliente Existente"></button>
                            <ul class="dropdown-menu dropdown-menu-end" id="client-dropdown-menu" style="max-height: 200px; overflow-y: auto;">
                                <li><a class="dropdown-item select-client" href="#" data-client-id="" data-client-name="Público General">Público General</a></li>
                                <li><hr class="dropdown-divider"></li>
                            </ul>
                        </div>
                    </div>
               </div>

               {{-- ================== TERRENO Y FINANCIAMIENTO (NUEVO) ================== --}}
               <div class="card mb-3 shadow-sm">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-map-marked-alt me-1"></i> Terreno
                        </h6>

                        <select class="form-select mb-3" id="terreno_id">
                            <option value="">Seleccione terreno</option>
                            @foreach($terrenos as $t)
                                <option value="{{ $t->id }}" data-precio="{{ $t->precio_total }}">
                                    {{ $t->codigo }} - {{ $t->nombre }} (${{ number_format($t->precio_total,2) }})
                                </option>
                            @endforeach
                        </select>

                        <h6 class="fw-bold text-primary mb-2">
                            <i class="fas fa-calendar-alt me-1"></i> Mensualidades
                        </h6>

                        <div id="mensualidades-options">
                            @foreach([12,24,36,48,60] as $m)
                                <label class="border rounded p-2 mb-2 d-flex justify-content-between align-items-center mensualidad-option">
                                    <input type="radio" name="mensualidades" value="{{ $m }}">
                                    <span class="fw-bold">{{ $m }}x</span>
                                    <span class="text-success">Sin intereses</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label class="fw-bold">Pago inicial</label>
                            <select class="form-select" id="pago_inicial">
                                <option value="2500">2500</option>
                                <option value="5000">5000</option>
                            </select>
                        </div>

                        <div class="mt-3">
                            <label class="fw-bold">Día de pago</label>
                            <select class="form-select" id="dia_pago">
                                @for($i=15;$i<=20;$i++)
                                    <option value="{{ $i }}">Día {{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="mt-3 text-center">
                            <small class="text-muted">Monto mensual</small>
                            <div class="fs-4 fw-bold text-success" id="monto_mensual_display">$0.00</div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            * El total de la venta se tomará del precio del terreno seleccionado.
                        </small>
                    </div>
               </div>
               {{-- ================== FIN TERRENO ================== --}}
                
                {{-- Carrito y Pago --}}
                <div id="cart" class="flex-grow-1 overflow-auto border rounded p-3 mb-3 bg-light shadow-sm">
                    <p class="text-center text-muted empty-cart-message">Añada productos para comenzar la venta.</p>
                </div>

                <div class="border-top pt-3">
                    {{-- Resumen Total --}}
                    <div class="d-flex justify-content-between fw-bold mb-1">
                        <span>SUBTOTAL:</span>
                        <span id="subtotal">$0.00</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold fs-5 text-danger mb-3">
                        <span>TOTAL A PAGAR:</span>
                        <span id="total">$0.00</span>
                    </div>

                    {{-- Botones --}}
                    <button id="process-payment" class="btn btn-success btn-lg w-100 mb-2 disabled" data-bs-toggle="modal" data-bs-target="#paymentModal">
                        <i class="fas fa-money-check-alt me-2"></i> Procesar Venta
                    </button>
                    <button id="cancel-order" class="btn btn-outline-danger w-100">
                        <i class="fas fa-times-circle me-2"></i> Cancelar Orden
                    </button>
                </div>
            </div>
        </div>

    @else
         <div class="alert alert-danger text-center mx-auto mt-5 shadow" style="max-width: 600px;">
             <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i> ¡Caja Cerrada!</h4>
             <p>No se puede realizar ninguna venta hasta que abras la caja.</p>
             <hr>
             <a href="{{ route('cajas.index') }}" class="btn btn-danger">
                 <i class="fas fa-box-open me-2"></i> Ir a Gestión de Caja para Abrir
             </a>
         </div>
    @endif
</div>

{{-- MODAL DE PAGO --}}
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="paymentModalLabel"><i class="fas fa-cash-register me-2"></i> Procesar Pago</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <label class="form-label fs-5">Total a Pagar:</label>
                    <div class="fs-1 fw-bolder text-danger" id="modal-total-display">$0.00</div>
                </div>
                <div class="mb-3">
                    <label for="modal-metodo-pago" class="form-label fw-bold">Método de Pago</label>
                    <select class="form-select form-select-lg" id="modal-metodo-pago">
                        <option value="efectivo" selected>Efectivo</option>
                        <option value="tarjeta">Tarjeta</option> 
                    </select>
                </div>
                <div id="efectivo-fields"> 
                    <div class="mb-3" id="monto-recibido-group">
                        <label for="modal-monto-recibido" class="form-label fw-bold">Monto Recibido</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">$</span>
                            <input type="number" step="0.01" min="0" class="form-control" id="modal-monto-recibido" placeholder="0.00">
                        </div>
                    </div>
                    <div class="mb-3 text-center" id="cambio-group">
                        <label class="form-label fs-5">Cambio a Entregar:</label>
                        <div class="fs-2 fw-bold text-info" id="modal-cambio-display">$0.00</div>
                    </div>
                </div>

                <div class="mb-3" id="tarjeta-fields" style="display: none;"> 
                    <label for="modal-folio-pago" class="form-label fw-bold">Folio / Autorización</label>
                    <input type="text" class="form-control form-control-lg" id="modal-folio-pago" placeholder="Ingrese N° de autorización">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success btn-lg" id="confirm-payment-btn" disabled> 
                    <i class="fas fa-check-circle me-2"></i> Confirmar Pago
                </button>
            </div>
        </div>
    </div>
</div>

{{-- IFRAME OCULTO PARA IMPRESIÓN --}}
<iframe id="print-frame" name="printFrame" style="position: absolute; top: -9999px; left: -9999px; width: 1px; height: 1px; visibility: hidden; border: 0;"></iframe>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables de Estado
    const cart = {}; 
    let selectedClientId = null; 
    let currentCategoryId = 'all'; 
    const clients = @json($clientes ?? []); 

    // ==== FINANCIAMIENTO (NUEVO) ====
    let precioTerreno = 0;
    let mensualidades = 0;
    let pagoInicial = 2500;

    const terrenoSelect = document.getElementById('terreno_id');
    const pagoInicialSelect = document.getElementById('pago_inicial');
    const diaPagoSelect = document.getElementById('dia_pago');
    const montoMensualDisplay = document.getElementById('monto_mensual_display');

    // ✅ NUEVO PANEL IZQUIERDA
    const btnTogglePagos = document.getElementById('btn-toggle-pagos');
    const panelPagos = document.getElementById('panel-pagos');
    const tablaPagosPreview = document.getElementById('tabla-pagos-preview');
    const btnContrato = document.getElementById('btn-contrato');

    const previewTotalTerreno = document.getElementById('preview-total-terreno');
    const previewPagoInicial = document.getElementById('preview-pago-inicial');
    const previewMontoMensual = document.getElementById('preview-monto-mensual');

    // Referencias del DOM
    const cartDiv = document.getElementById('cart');
    const subtotalSpan = document.getElementById('subtotal');
    const totalSpan = document.getElementById('total');
    const processButton = document.getElementById('process-payment'); 
    const cancelButton = document.getElementById('cancel-order');
    const temporalClientInput = document.getElementById('temporal-client-name'); 
    const clientDropdownMenu = document.getElementById('client-dropdown-menu'); 
    const productListDiv = document.getElementById('product-list');
    const categoryFilters = document.querySelectorAll('.category-filter');
    const searchInput = document.getElementById('product-search'); 
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';
    const emptyCartMessageHTML = '<p class="text-center text-muted empty-cart-message">Añada productos para comenzar la venta.</p>';
    const paymentModalElement = document.getElementById('paymentModal');
    let paymentModal = null;
    if (paymentModalElement && typeof bootstrap !== 'undefined') { 
         paymentModal = new bootstrap.Modal(paymentModalElement); 
    }
    const modalTotalDisplay = document.getElementById('modal-total-display');
    const modalMetodoPago = document.getElementById('modal-metodo-pago');
    const modalMontoRecibido = document.getElementById('modal-monto-recibido');
    const modalCambioDisplay = document.getElementById('modal-cambio-display');
    const confirmPaymentBtn = document.getElementById('confirm-payment-btn');
    const efectivoFields = document.getElementById('efectivo-fields'); 
    const tarjetaFields = document.getElementById('tarjeta-fields'); 
    const modalFolioPago = document.getElementById('modal-folio-pago'); 
    const printFrame = document.getElementById('print-frame');

    // Mensualidades radio
    document.querySelectorAll('input[name="mensualidades"]').forEach(radio => {
        radio.addEventListener('change', () => {
            mensualidades = parseInt(radio.value, 10);
            calcularMensualidad();
            renderPreviewPagos();
        });
    });

    if (terrenoSelect) {
        terrenoSelect.addEventListener('change', function () {
            precioTerreno = parseFloat(this.selectedOptions[0]?.dataset.precio || 0);
            calcularMensualidad();
            renderPreviewPagos();
        });
    }

    if (pagoInicialSelect) {
        pagoInicialSelect.addEventListener('change', function () {
            pagoInicial = parseFloat(this.value || 0);
            calcularMensualidad();
            renderPreviewPagos();
        });
    }

    if (diaPagoSelect) {
        diaPagoSelect.addEventListener('change', function () {
            renderPreviewPagos();
        });
    }

    function calcularMensualidad() {
        if (!precioTerreno || !mensualidades) {
            if (montoMensualDisplay) montoMensualDisplay.textContent = '$0.00';
            return;
        }
        const monto = ((precioTerreno - pagoInicial) / mensualidades);
        const safeMonto = isFinite(monto) ? monto : 0;
        if (montoMensualDisplay) montoMensualDisplay.textContent = '$' + safeMonto.toFixed(2);

        // TOTAL es el terreno
        if (totalSpan) totalSpan.textContent = `$${precioTerreno.toFixed(2)}`;
        if (modalTotalDisplay) modalTotalDisplay.textContent = `$${precioTerreno.toFixed(2)}`;
    }

    // ✅ panel pagos toggle
    if (btnTogglePagos && panelPagos) {
        btnTogglePagos.addEventListener('click', () => {
            panelPagos.style.display = (panelPagos.style.display === 'none') ? 'block' : 'none';
            renderPreviewPagos();
        });
    }

    // ✅ preview tabla pagos (estado por mensualidad = PENDIENTE en preview)
    function renderPreviewPagos() {
        if (!tablaPagosPreview) return;

        const diaPago = diaPagoSelect ? parseInt(diaPagoSelect.value, 10) : 15;

        // Resumen
        if (previewTotalTerreno) previewTotalTerreno.textContent = `$${(precioTerreno || 0).toFixed(2)}`;
        if (previewPagoInicial) previewPagoInicial.textContent = `$${(pagoInicial || 0).toFixed(2)}`;

        if (!precioTerreno || !mensualidades) {
            if (previewMontoMensual) previewMontoMensual.textContent = '$0.00';
            tablaPagosPreview.innerHTML = '';
            return;
        }

        const montoMensual = (precioTerreno - pagoInicial) / mensualidades;
        if (previewMontoMensual) previewMontoMensual.textContent = `$${(isFinite(montoMensual) ? montoMensual : 0).toFixed(2)}`;

        // Fecha base = hoy + 5 días
        const base = new Date();
        base.setDate(base.getDate() + 5);

        // Primer pago en el día elegido del mes
        let fecha = new Date(base.getFullYear(), base.getMonth(), diaPago);
        if (fecha < base) {
            fecha = new Date(base.getFullYear(), base.getMonth() + 1, diaPago);
        }

        let html = `
            <div class="table-responsive">
            <table class="table table-sm table-bordered mt-2">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Vencimiento</th>
                        <th>Monto</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
        `;

        for (let i = 1; i <= mensualidades; i++) {
            const yyyy = fecha.getFullYear();
            const mm = String(fecha.getMonth() + 1).padStart(2, '0');
            const dd = String(fecha.getDate()).padStart(2, '0');
            const f = `${yyyy}-${mm}-${dd}`;

            html += `
                <tr>
                    <td>${i}</td>
                    <td>${f}</td>
                    <td>$${(isFinite(montoMensual) ? montoMensual : 0).toFixed(2)}</td>
                    <td><span class="badge bg-secondary">PENDIENTE</span></td>
                </tr>
            `;

            fecha = new Date(fecha.getFullYear(), fecha.getMonth() + 1, diaPago);
        }

        html += `
                </tbody>
            </table>
            </div>
        `;

        tablaPagosPreview.innerHTML = html;
    }

    // ==========================================================
    // ACTUALIZACIÓN DEL CARRITO (SIN CAMBIOS EN TU LÓGICA)
    // ==========================================================
    function updateCartQuantity(id, newQty) {
        if (!cart[id]) return;
        const item = cart[id];
        const stock = item.stock;
        let quantity = parseInt(newQty, 10);

        if (isNaN(quantity)) quantity = 1;

        if (quantity > stock) {
            alert(`Stock insuficiente. Solo quedan ${stock} unidades de ${item.name}.`);
            quantity = stock;
        }

        if (quantity <= 0) {
            delete cart[id];
        } else {
            item.qty = quantity;
        }
        updateCartUI();
    }

    function updateCartUI() {
        let subtotal = 0;
        let itemCount = 0;
        if (!cartDiv) return;
        cartDiv.innerHTML = ''; 
        
        for (const id in cart) { 
            const item = cart[id];
            const itemTotal = item.price * item.qty;
            subtotal += itemTotal;
            itemCount++;
            const itemElement = document.createElement('div');
            itemElement.className = 'd-flex justify-content-between border-bottom py-2 align-items-center cart-item';
            itemElement.innerHTML = `
                <div class="flex-grow-1 me-2 d-flex align-items-center">
                    <input type="number" class="form-control form-control-sm cart-item-qty" value="${item.qty}" data-id="${id}"
                           min="0" max="${item.stock}" style="width: 60px; text-align: center; margin-right: 10px;">
                    <span class="fw-bold">${item.name}</span>
                </div>
                <div class="d-flex align-items-center">
                    <button class="btn btn-sm btn-outline-danger me-2 cart-action" data-id="${id}" data-action="remove" title="Restar">-</button>
                    <span class="fw-bold me-2" style="min-width: 60px; text-align: right;">$${itemTotal.toFixed(2)}</span>
                    <button class="btn btn-sm btn-outline-success cart-action" data-id="${id}" data-action="add" title="Añadir">+</button>
                </div>
            `;
            cartDiv.appendChild(itemElement);
        }

        if (itemCount === 0) { 
            cartDiv.innerHTML = emptyCartMessageHTML;
            if (processButton) processButton.classList.add('disabled');
        } else { 
            if (processButton) processButton.classList.remove('disabled');
        }

        if (subtotalSpan) subtotalSpan.textContent = `$${subtotal.toFixed(2)}`;

        const totalFinal = precioTerreno > 0 ? precioTerreno : subtotal;
        if (totalSpan) totalSpan.textContent = `$${totalFinal.toFixed(2)}`;
        if (modalTotalDisplay) modalTotalDisplay.textContent = `$${totalFinal.toFixed(2)}`;
    }

    function addItem(id, name, price, stock) {
        stock = parseInt(stock) || 0; 
        if (stock <= 0) return;
        
        const currentQty = cart[id] ? cart[id].qty : 0;
        cart[id] = { name, price, qty: currentQty, stock };
        updateCartQuantity(id, currentQty + 1);
        cartDiv.scrollTop = cartDiv.scrollHeight;
    }

    if (cartDiv) { 
        cartDiv.addEventListener('click', function(e) { 
            const target = e.target;
            if (target.classList.contains('cart-action')) {
                const id = target.dataset.id;
                const action = target.dataset.action;
                const item = cart[id];
                if (item) {
                    const newQty = action === 'add' ? item.qty + 1 : item.qty - 1;
                    updateCartQuantity(id, newQty);
                }
            }
        }); 
        
        cartDiv.addEventListener('input', function(e) {
            if (e.target.classList.contains('cart-item-qty')) {
                const id = e.target.dataset.id;
                const newQty = e.target.value;
                updateCartQuantity(id, newQty);
            }
        });
    }

    if (productListDiv) { 
        productListDiv.addEventListener('click', function(e) { 
            const card = e.target.closest('.product-card');
            if (card) {
                const id = card.dataset.id;
                const price = parseFloat(card.dataset.price);
                const name = card.dataset.name;
                const stock = parseInt(card.dataset.stock);
                addItem(id, name, price, stock); 
            }
        }); 
    }

    // ==========================================================
    // CLIENTES (id / nombre)
    // ==========================================================
    function populateClientDropdown() { 
        if (!clientDropdownMenu) return; 
        const items = clientDropdownMenu.querySelectorAll('li:not(:first-child):not(:nth-child(2))');
        items.forEach(item => item.remove());

        if (clients && clients.length > 0) { 
            clients.forEach(client => {
                const li = document.createElement('li');
                const a = document.createElement('a');
                a.className = 'dropdown-item select-client';
                a.href = '#';
                a.dataset.clientId = client.id; 
                a.dataset.clientName = client.nombre; 
                a.textContent = client.nombre;
                li.appendChild(a);
                clientDropdownMenu.appendChild(li);
            });
        } else {
            const li = document.createElement('li');
            li.innerHTML = '<span class="dropdown-item text-muted">No hay clientes</span>';
            clientDropdownMenu.appendChild(li);
        }
    }

    function updateSelectedClient(id, name) { 
        selectedClientId = id ? parseInt(id, 10) : null;
        if(temporalClientInput){
            temporalClientInput.value = (id || name === 'Público General') ? name : ''; 
            temporalClientInput.placeholder = 'Público General'; 
            if (!id && name === 'Público General') temporalClientInput.value = '';
        }
    }

    if(clientDropdownMenu){
        clientDropdownMenu.addEventListener('click', function(e) { 
            e.preventDefault();
            if (e.target.classList.contains('select-client')) {
                updateSelectedClient(e.target.dataset.clientId, e.target.dataset.clientName);
            }
        });
    }

    if(temporalClientInput){
        temporalClientInput.addEventListener('input', function() { 
            const typedName = this.value.trim();
            const existingClient = clients.find(c => (c.nombre || '').toLowerCase() === typedName.toLowerCase());
            selectedClientId = existingClient ? existingClient.id : null; 
        });
    }

    // ==========================================================
    // MODAL PAGO
    // ==========================================================
    if (paymentModalElement) {
        paymentModalElement.addEventListener('show.bs.modal', function() {
            if (Object.keys(cart).length === 0) {
                if(paymentModal) paymentModal.hide(); 
                return;
            }

            if(modalMontoRecibido) modalMontoRecibido.value = ''; 
            if(modalFolioPago) modalFolioPago.value = ''; 
            if(modalMetodoPago) modalMetodoPago.value = 'efectivo'; 
            
            togglePaymentFields(); 
            calculateChange(); 
        });
    }

    if (modalMetodoPago) { modalMetodoPago.addEventListener('change', togglePaymentFields); }

    function togglePaymentFields() { 
        if (!modalMetodoPago || !efectivoFields || !tarjetaFields) return;
        const isCash = modalMetodoPago.value === 'efectivo';
        efectivoFields.style.display = isCash ? 'block' : 'none';
        tarjetaFields.style.display = isCash ? 'none' : 'block'; 
        
        if (!isCash) {
            if(modalMontoRecibido && totalSpan) modalMontoRecibido.value = totalSpan.textContent.replace('$', ''); 
            calculateChange(); 
        } else {
            if(modalMontoRecibido) modalMontoRecibido.value = ''; 
            calculateChange(); 
        }
    }

    if (modalMontoRecibido) { modalMontoRecibido.addEventListener('input', calculateChange); }
    if (modalFolioPago) { modalFolioPago.addEventListener('input', calculateChange); } 
    
    function calculateChange() { 
        if (!modalMetodoPago || !modalCambioDisplay || !confirmPaymentBtn || !totalSpan) return; 
        const metodo = modalMetodoPago.value;
        const total = parseFloat(totalSpan.textContent.replace('$', ''));

        if (metodo === 'efectivo') {
            const recibido = modalMontoRecibido ? (parseFloat(modalMontoRecibido.value) || 0) : 0;
            const cambio = recibido - total;
            modalCambioDisplay.textContent = `$${Math.max(0, cambio).toFixed(2)}`; 
            confirmPaymentBtn.disabled = (recibido < total); 
        } else {
            modalCambioDisplay.textContent = '$0.00'; 
            const folio = modalFolioPago ? modalFolioPago.value.trim() : '';
            confirmPaymentBtn.disabled = (folio === ''); 
        }
    }

    // ==========================================================
    // PROCESAR PAGO (HABILITA CONTRATO PDF)
    // ==========================================================
    if (confirmPaymentBtn) { 
        confirmPaymentBtn.addEventListener('click', async function() { 
            if (Object.keys(cart).length === 0 || !totalSpan) return;

            if (!terrenoSelect || !terrenoSelect.value) { alert('Seleccione un terreno.'); return; }
            if (!mensualidades || mensualidades <= 0) { alert('Seleccione mensualidades.'); return; }

            const detalles = Object.keys(cart).map(id => ({ 
                producto_id: id,
                cantidad: cart[id].qty,
                precio_unitario: cart[id].price,
                importe: cart[id].price * cart[id].qty 
            }));

            const total = parseFloat(totalSpan.textContent.replace('$', ''));
            const metodoPago = modalMetodoPago ? modalMetodoPago.value : 'efectivo';

            let montoRecibido = modalMontoRecibido ? (parseFloat(modalMontoRecibido.value) || 0) : total;
            let montoEntregado = 0;
            const folioTarjeta = modalFolioPago ? modalFolioPago.value.trim() : null;

            if (metodoPago === 'efectivo') {
                montoEntregado = Math.max(0, montoRecibido - total); 
                if (montoRecibido < total) { alert('Monto recibido insuficiente.'); return; }
            } else {
                montoRecibido = total;
                if (!folioTarjeta) { alert('Ingrese folio de tarjeta.'); return; }
            }

            const fechaCompra = new Date().toISOString().slice(0,10);
            const payload = {
                _token: csrfToken,
                cliente_id: selectedClientId,
                metodo_pago: metodoPago,
                total: precioTerreno,
                monto_recibido: montoRecibido,
                monto_entregado: montoEntregado,
                detalles: detalles,
                terreno_id: parseInt(terrenoSelect.value, 10),
                fecha_compra: fechaCompra,
                mensualidades: mensualidades,
                pago_inicial: pagoInicial,
                dia_pago: diaPagoSelect ? parseInt(diaPagoSelect.value, 10) : 15
            };

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Procesando...';
            
            try {
                const response = await fetch("{{ route('ventas.store') }}", { 
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload)
                });

                const result = await response.json(); 

                if (response.ok) {
                    if(paymentModal) paymentModal.hide(); 

                    // imprimir ticket
                    const printUrl = `{{ url('/ventas/imprimir') }}/${result.venta_id}`;
                    if (printFrame) printFrame.src = printUrl;

                    // ✅ habilitar contrato PDF con la venta recién creada
                    if (btnContrato) {
                        btnContrato.disabled = false;
                        btnContrato.onclick = () => {
                            window.open(`{{ url('/ventas/contrato') }}/${result.venta_id}`, '_blank');
                        };
                    }

                    // limpiar
                    for (const id in cart) { delete cart[id]; }
                    updateSelectedClient(null, 'Público General'); 
                    updateCartUI();

                } else { 
                    let errMsg = result.message || 'Error.';
                    if (result.errors) {
                        errMsg += '\nDetalles:\n';
                        for(const f in result.errors) errMsg += `- ${result.errors[f].join(', ')}\n`;
                    }
                    alert('Error:\n' + errMsg);
                }
            } catch (e) { 
                console.error('Error al procesar venta:', e); 
                alert('Error de conexión o problema en el script.');
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-check-circle me-2"></i> Confirmar Pago';
            }
        }); 
    }

    // Inicializar
    updateCartUI();
    populateClientDropdown();
    updateSelectedClient(null, 'Público General');
}); 
</script>

<style>
.product-card:hover { 
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
    transition: all 0.2s ease-in-out;
}
.fs-sm { font-size: 0.85rem; } 

.cart-item-qty { -moz-appearance: textfield; }
.cart-item-qty::-webkit-outer-spin-button,
.cart-item-qty::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }

.mensualidad-option { cursor: pointer; user-select: none; }
.mensualidad-option:hover { background: #f8f9fa; }
.mensualidad-option input[type="radio"] { margin-right: 10px; }
</style>

@endsection

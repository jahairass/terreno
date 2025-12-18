<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Ticket Venta #{{ $venta->id }}</title>
    <style>
        /* Estilos básicos para un ticket de 80mm */
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #000;
            margin: 0;
            padding: 5px;
            width: 78mm; /* Ancho aprox 80mm menos márgenes */
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: bold; }
        .mt-1 { margin-top: 5px; }
        .mt-2 { margin-top: 10px; }
        .mb-0 { margin-bottom: 0; }
        h3 { font-size: 14px; margin: 5px 0; }
        hr { border: 0; border-top: 1px dashed #000; margin: 5px 0; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 2px 0; }
        .item-row td { vertical-align: top; }
        .total-row td { padding-top: 5px; border-top: 1px dashed #000; }
    </style>
</head>
<body>
    <div class="text-center">
        <h3 class="mb-0">Panadería "Tu Nombre"</h3>
        <p class="mb-0">Dirección de tu Panadería, Ciudad</p>
        <p class="mb-0">Tel: 123-456-7890</p>
    </div>
    <hr>
    <div>
        <p class="mb-0">Folio Venta: {{ $venta->id }}</p>
        <p class="mb-0">Fecha: {{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</p>
        <p class="mb-0">Cajero: {{ $venta->user->name ?? 'N/A' }}</p>
        <p class="mb-0">Cliente: {{ $venta->cliente->Nombre ?? 'Público General' }}</p>
    </div>
    <hr>
    <table>
        <thead>
            <tr>
                <th class="text-left">Cant.</th>
                <th class="text-left">Producto</th>
                <th class="text-right">Importe</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($venta->detalles as $detalle)
            <tr class="item-row">
                <td>{{ $detalle->cantidad }}</td>
                <td>
                    {{ $detalle->producto->nombre ?? 'Producto no encontrado' }}
                    {{-- Mostrar precio unitario si es diferente al importe --}}
                    @if($detalle->cantidad > 1)
                        <br><small>(${{ number_format($detalle->precio_unitario, 2) }} c/u)</small>
                    @endif
                </td>
                <td class="text-right">${{ number_format($detalle->importe, 2) }}</td>
            </tr>
            @endforeach
            
            <tr class="total-row">
                <td colspan="2" class="fw-bold text-right">TOTAL:</td>
                <td class="fw-bold text-right">${{ number_format($venta->total, 2) }}</td>
            </tr>
            
            {{-- Información de pago --}}
            <tr class="total-row">
                <td colspan="2" class="text-right">Método Pago:</td>
                <td class="text-right">{{ ucfirst($venta->metodo_pago) }}</td>
            </tr>
            @if($venta->metodo_pago == 'efectivo')
            <tr>
                <td colspan="2" class="text-right">Recibido:</td>
                <td class="text-right">${{ number_format($venta->monto_recibido, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">Cambio:</td>
                <td class="text-right">${{ number_format($venta->monto_entregado, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>
    <hr>
    <div class="text-center mt-2">
        <p>¡Gracias por su compra!</p>
    </div>
</body>
</html>
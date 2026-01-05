<?php

namespace App\Http\Controllers;

use App\Models\Terreno;
use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Cliente;
use App\Models\PagoVenta; // ✅ NUEVO

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use PDF;

class VentaController extends Controller
{
    /**
     * Muestra la interfaz del Punto de Venta (TPV).
     */
    public function tpv()
    {
        // 1. Verificar si la caja está abierta
        $cajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        if (!$cajaAbierta) {
            return redirect()->route('cajas.index')
                ->with('error', 'Debes abrir una caja antes de iniciar una venta.');
        }

        // 2. Datos TPV
        $categorias = Categoria::orderBy('nombre')->get();

        $productos = Producto::with('inventario', 'categoria')
            ->whereHas('inventario', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->orderBy('nombre')
            ->get();

        $clientes = Cliente::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        // ✅ Terrenos disponibles
        $terrenos = Terreno::where('estado', 'disponible')
            ->orderBy('id')
            ->get();

        return view('ventas.tpv', compact('cajaAbierta', 'categorias', 'productos', 'clientes', 'terrenos'));
    }

    /**
     * Almacena una nueva venta procesada desde el TPV (Terreno + Financiamiento + Pagos automáticos).
     */
    public function store(Request $request)
    {
        // ✅ VALIDACIÓN COMPLETA
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,id',

            // NUEVO: Terreno + financiamiento
            'terreno_id' => 'required|exists:terrenos,id',
            'fecha_compra' => 'required|date',
            'mensualidades' => 'required|in:12,24,36,48,60',
            'pago_inicial' => 'required|in:2500,5000',
            'dia_pago' => 'required|integer|min:15|max:20',

            // TPV existente
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia,credito',
            'total' => 'required|numeric|min:0.01',
            'monto_recibido' => 'nullable|numeric|min:0',
            'monto_entregado' => 'nullable|numeric|min:0',

            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.importe' => 'required|numeric|min:0',
        ]);

        // 2. Caja abierta
        $cajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        if (!$cajaAbierta) {
            return response()->json(['message' => 'Error: La caja está cerrada.'], 400);
        }

        // 3. Terreno disponible (lock para evitar doble venta)
        $terreno = Terreno::where('id', $request->terreno_id)->lockForUpdate()->first();

        if (!$terreno) {
            return response()->json(['message' => 'Terreno no encontrado.'], 404);
        }

        if ($terreno->estado !== 'disponible') {
            return response()->json(['message' => 'El terreno ya no está disponible.'], 422);
        }

        // Forzar total = precio del terreno (seguridad)
        $totalTerreno   = (float) $terreno->precio_total;
        $pagoInicial    = (float) $request->pago_inicial;
        $mensualidades  = (int) $request->mensualidades;
        $diaPago        = (int) $request->dia_pago;

        $montoFinanciado = max(0, $totalTerreno - $pagoInicial);
        $montoMensual    = $mensualidades > 0 ? round($montoFinanciado / $mensualidades, 2) : 0;

        $fechaCompra     = Carbon::parse($request->fecha_compra);
        $fechaPrimerPago = $fechaCompra->copy()->addDays(5);

        DB::beginTransaction();

        try {
            // 4. Crear venta con campos nuevos
            $venta = Venta::create([
                'terreno_id' => $terreno->id,
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),

                'fecha_hora' => now(),
                'fecha_compra' => $fechaCompra->toDateString(),
                'mensualidades' => $mensualidades,
                'pago_inicial' => $pagoInicial,
                'monto_mensual' => $montoMensual,
                'dia_pago' => $diaPago,
                'fecha_primer_pago' => $fechaPrimerPago->toDateString(),
                'estado_venta' => 'financiado',

                'metodo_pago' => $request->metodo_pago,
                'total' => $totalTerreno,
                'monto_recibido' => $request->monto_recibido ?? $totalTerreno,
                'monto_entregado' => $request->monto_entregado ?? 0,
            ]);

            // ==========================================================
            // ✅ NUEVO: GENERAR CALENDARIO DE PAGOS (PASO 3)
            // Justo después de crear la venta
            // ==========================================================
            $pagos = [];

            // Base = compra + 5 días
            $fechaBase = Carbon::parse($venta->fecha_compra)->addDays(5);

            // Pago 1: el día 15-20 del mes que corresponda
            $fechaPago = $fechaBase->copy();
            $fechaPago->day = $diaPago;

            if ($fechaPago->lt($fechaBase)) {
                $fechaPago = $fechaPago->addMonthNoOverflow();
                $fechaPago->day = $diaPago;
            }

            for ($i = 1; $i <= (int) $venta->mensualidades; $i++) {
                $pagos[] = [
                    'venta_id' => $venta->id,
                    'numero_pago' => $i,
                    'fecha_vencimiento' => $fechaPago->toDateString(),
                    'monto' => $venta->monto_mensual,
                    'estado' => 'pendiente',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $fechaPago = $fechaPago->copy()->addMonthNoOverflow();
                $fechaPago->day = $diaPago;
            }

            // Inserción masiva
            DB::table('pago_ventas')->insert($pagos);

            // 5. Detalles + stock
            foreach ($request->detalles as $detalle) {
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'importe' => $detalle['importe'],
                ]);

                $inventario = Inventario::where('producto_id', $detalle['producto_id'])->first();

                if (!$inventario) {
                    throw ValidationException::withMessages([
                        'inventario' => 'Error: No se encontró inventario para el producto ID ' . $detalle['producto_id']
                    ]);
                }

                if ($inventario->stock < $detalle['cantidad']) {
                    throw ValidationException::withMessages([
                        'stock' => 'Stock insuficiente para el producto ID ' . $detalle['producto_id']
                    ]);
                }

                $inventario->decrement('stock', $detalle['cantidad']);
            }

            // 6. Marcar terreno como vendido
            $terreno->estado = 'vendido';
            $terreno->save();

            DB::commit();

            return response()->json([
                'message' => 'Venta registrada exitosamente.',
                'venta_id' => $venta->id
            ], 201);

        } catch (ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error de validación.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Error interno al procesar la venta. Verifique los logs.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Genera un PDF del ticket de venta.
     */
    public function generarTicketPDF(Venta $venta)
    {
        $venta->load('user', 'cliente', 'terreno', 'detalles.producto');
        $pdf = PDF::loadView('ventas.ticket_pdf', compact('venta'));
        $pdf->setPaper([0, 0, 226.77, 800]);

        return $pdf->stream('ticket_venta_' . $venta->id . '.pdf');
    }

    /**
     * Contrato PDF con calendario de pagos.
     */
    public function contratoPDF(Venta $venta)
    {
        $venta->load('user', 'cliente', 'terreno', 'pagos');
        $pdf = PDF::loadView('ventas.contrato_pdf', compact('venta'));
        $pdf->setPaper('letter');

        return $pdf->stream('contrato_venta_' . $venta->id . '.pdf');
    }

    /**
     * Vista envoltorio para imprimir PDF.
     */
    public function imprimirTicket(Venta $venta)
    {
        $urlPdf = route('ventas.ticket', $venta);
        return view('ventas.imprimir_pdf', compact('urlPdf'));
    }
}

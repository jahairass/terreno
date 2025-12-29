<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Categoria;
use App\Models\all;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use App\Models\Cliente;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

use PDF;

class VentaController extends Controller
{
    /**
     * Muestra la interfaz del Punto de Venta (TPV).
     * Requiere el permiso 'mostrar' del módulo 'ventas'.
     */
    public function tpv()
    {
        // 1. Verificar si la caja está abierta
        $cajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        // Si no hay caja abierta, redirigir al módulo de cajas
        if (!$cajaAbierta) {
            return redirect()->route('cajas.index')
                ->with('error', 'Debes abrir una caja antes de iniciar una venta.');
        }

        // 2. Obtener datos para la TPV
        $categorias = Categoria::orderBy('nombre')->get();

        // Productos con stock > 0
        $productos = Producto::with('inventario', 'categoria')
            ->whereHas('inventario', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->orderBy('nombre')
            ->get();

        // ✅ CORRECCIÓN: usar columnas reales estándar (id, nombre)
        $clientes = Cliente::select('id', 'nombre')
            ->orderBy('nombre')
            ->get();

        // La vista espera estas variables
        return view('ventas.tpv', compact('cajaAbierta', 'categorias', 'productos', 'clientes'));
    }

    /**
     * Almacena una nueva venta procesada desde el TPV.
     * Requiere el permiso 'alta' del módulo 'ventas'.
     */
    public function store(Request $request)
    {
        // 1. Validar datos básicos de la venta
        $request->validate([
            // ✅ CORRECCIÓN: exists apunta a clientes,id
            'cliente_id' => 'nullable|exists:clientes,id',

            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia,credito',
            'total' => 'required|numeric|min:0.01',

            'detalles' => 'required|array|min:1',
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.importe' => 'required|numeric|min:0',
        ]);

        // 2. Verificar si la caja está abierta (doble chequeo)
        $cajaAbierta = Caja::where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        if (!$cajaAbierta) {
            return response()->json(['message' => 'Error: La caja está cerrada.'], 400);
        }

        DB::beginTransaction();

        try {
            // 3. Crear el registro de la Venta
            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'user_id' => Auth::id(),
                'fecha_hora' => now(),
                'metodo_pago' => $request->metodo_pago,
                'total' => $request->total,
                'monto_recibido' => $request->monto_recibido ?? $request->total,
                'monto_entregado' => $request->monto_entregado ?? 0,
            ]);

            // 4. Crear los Detalles de la Venta y Actualizar Stock
            foreach ($request->detalles as $detalle) {

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'importe' => $detalle['importe'],
                ]);

                // Actualizar el stock del producto
                $inventario = Inventario::where('producto_id', $detalle['producto_id'])->first();

                if (!$inventario) {
                    throw ValidationException::withMessages([
                        'inventario' => 'Error: No se encontró registro de inventario para el producto ID ' . $detalle['producto_id']
                    ]);
                }

                if ($inventario->stock < $detalle['cantidad']) {
                    throw ValidationException::withMessages([
                        'stock' => 'Stock insuficiente para el producto ID ' . $detalle['producto_id']
                    ]);
                }

                // Reducir stock
                $inventario->decrement('stock', $detalle['cantidad']);
            }

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
        $venta->load('user', 'cliente', 'detalles.producto');

        $pdf = PDF::loadView('ventas.ticket_pdf', compact('venta'));

        // 80mm de ancho aprox
        $pdf->setPaper([0, 0, 226.77, 800]);

        return $pdf->stream('ticket_venta_' . $venta->id . '.pdf');
    }

    /**
     * Muestra una vista "envoltorio" que carga el PDF y fuerza la impresión.
     */
    public function imprimirTicket(Venta $venta)
    {
        $urlPdf = route('ventas.ticket', $venta);

        return view('ventas.imprimir_pdf', compact('urlPdf'));
    }
}

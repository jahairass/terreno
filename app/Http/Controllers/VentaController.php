<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\DetalleVenta;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;// Para manejo de errores
use App\Models\Cliente; 
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
            return redirect()->route('cajas.index')->with('error', 'Debes abrir una caja antes de iniciar una venta.');
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

        $clientes = Cliente::select('idCli', 'Nombre')->orderBy('Nombre')->get();
        
        // La vista espera estas variables
        return view('ventas.tpv', compact('cajaAbierta', 'categorias', 'productos','clientes'));
    }

    /**
     * Almacena una nueva venta procesada desde el TPV.
     * Requiere el permiso 'alta' del módulo 'ventas'.
     */
    public function store(Request $request)
    {
        // El middleware 'permiso:ventas,alta' protege esta función
        
        // 1. Validar datos básicos de la venta
        $request->validate([
            'cliente_id' => 'nullable|exists:clientes,idCli',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,transferencia,credito',
            'total' => 'required|numeric|min:0.01',
            'detalles' => 'required|array|min:1', // Asegura que haya al menos un producto
            'detalles.*.producto_id' => 'required|exists:productos,id',
            'detalles.*.cantidad' => 'required|integer|min:1',
            'detalles.*.precio_unitario' => 'required|numeric|min:0',
            'detalles.*.importe' => 'required|numeric|min:0',
        ]);

        // 2. Verificar si la caja está abierta (doble chequeo)
        $cajaAbierta = Caja::where('user_id', Auth::id())->where('estado', 'abierta')->first();
        if (!$cajaAbierta) {
            return response()->json(['message' => 'Error: La caja está cerrada.'], 400); // Error para AJAX
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
                'monto_recibido' => $request->monto_recibido ?? $request->total, // Asumir pago exacto si no se envía
                'monto_entregado' => $request->monto_entregado ?? 0,
            ]);

            // 4. Crear los Detalles de la Venta y Actualizar Stock
            foreach ($request->detalles as $detalle) {
                // Crear el detalle
                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $detalle['producto_id'],
                    'cantidad' => $detalle['cantidad'],
                    'precio_unitario' => $detalle['precio_unitario'],
                    'importe' => $detalle['importe'],
                    // 'descripcion' => $detalle['descripcion'] ?? null, // Si tuvieras descripciones
                ]);

                // Actualizar el stock del producto
                $inventario = Inventario::where('producto_id', $detalle['producto_id'])->first();
                if ($inventario) {
                    // Validar si hay suficiente stock
                    if ($inventario->stock < $detalle['cantidad']) {
                        // Si no hay stock, deshacer la transacción
                        throw ValidationException::withMessages([
                            'stock' => 'Stock insuficiente para el producto ID ' . $detalle['producto_id']
                        ]);
                    }
                    // Reducir stock (CRÍTICO)
                    $inventario->decrement('stock', $detalle['cantidad']);
                } else {
                    // Error si un producto vendido no tiene registro de inventario
                     throw ValidationException::withMessages([
                         'inventario' => 'Error: No se encontró registro de inventario para el producto ID ' . $detalle['producto_id']
                     ]);
                }
            }

            DB::commit();

            // Respuesta exitosa para la llamada AJAX
            return response()->json(['message' => 'Venta registrada exitosamente.', 'venta_id' => $venta->id], 201);

        } catch (ValidationException $e) {
            DB::rollBack();
            // Retorna errores de validación (ej. stock insuficiente)
            return response()->json(['message' => 'Error de validación.', 'errors' => $e->errors()], 422);
            
        } catch (\Exception $e) {
            DB::rollBack();
            // Retorna un error genérico para AJAX
            return response()->json(['message' => 'Error interno al procesar la venta. Verifique los logs.'], 500);
        }
    }
        // ***** MÉTODO NUEVO PARA GENERAR EL TICKET PDF *****
    /**
     * Genera un PDF del ticket de venta.
     */
    public function generarTicketPDF(Venta $venta)
    {
        // Cargar las relaciones necesarias para el ticket
        $venta->load('user', 'cliente', 'detalles.producto');

        // Cargar la vista 'ticket_pdf' con los datos
        $pdf = PDF::loadView('ventas.ticket_pdf', compact('venta'));
        
        // Configurar el tamaño (ej. 80mm de ancho para ticketera)
        $pdf->setPaper([0, 0, 226.77, 800]); // Ancho 80mm, altura larga

        // Mostrar el PDF en el navegador
        return $pdf->stream('ticket_venta_' . $venta->id . '.pdf');
    }

    /**
     * Muestra una vista "envoltorio" que carga el PDF y fuerza la impresión.
     */
    public function imprimirTicket(Venta $venta)
    {
        // Generamos la URL a la ruta que SÍ crea el PDF
        $urlPdf = route('ventas.ticket', $venta);
        
        // Pasamos esa URL a la nueva vista 'imprimir_pdf'
        return view('ventas.imprimir_pdf', compact('urlPdf'));
    }
}

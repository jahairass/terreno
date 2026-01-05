<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\MovimientoCaja;
use App\Models\Cliente;
use App\Models\Compra;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CajaController extends Controller
{
    /**
     * ✅ NUEVO: ya no manejamos caja abierta/cerrada.
     * Esta función solo muestra la pantalla de cobros tipo "Cobrar y Entregar".
     */
    public function index()
    {
        // Clientes para selector
        $clientes = Cliente::orderBy('Nombre')->get();

        // Historial de movimientos recientes
        $movimientos = MovimientoCaja::with('user')
            ->orderBy('created_at', 'desc')
            ->take(30)
            ->get();

        return view('cajas.index', compact('clientes', 'movimientos'));
    }

    /**
     * ✅ NUEVO: Obtener/crear una caja "sistema" para registrar movimientos,
     * ya que eliminamos la apertura/cierre.
     */
    private function cajaSistemaId(): int
    {
        // Si ya existe una caja con estado 'sistema' úsala.
        // Si no, crea una.
        $caja = Caja::firstOrCreate(
            ['estado' => 'sistema'],
            [
                'user_id' => Auth::id(),
                'fecha_hora_apertura' => now(),
                'saldo_inicial' => 0,
            ]
        );

        return (int) $caja->id;
    }

    // ===================================================================
    // =================== ✅ REGISTRAR COBRO (SIN CAJA ABIERTA) ==========
    // ===================================================================
    public function registrarCobro(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,idCli',
            'mensualidad' => 'required|numeric|min:1',
            'mensualidades' => 'required|integer|min:1',
            'fecha_vencimiento' => 'required|date',
            'fecha_pago' => 'required|date',
            'tipo' => 'required|in:normal,adelanto,liquidacion',
            'metodo_pago' => 'required|in:efectivo,transferencia',
            'notas' => 'nullable|string|max:255',
        ]);

        // 1) Cliente
        $cliente = Cliente::where('idCli', $request->cliente_id)->first();
        if (!$cliente) {
            return redirect()->route('cajas.index')->with('error', 'Cliente no encontrado.');
        }

        // 2) Compra más reciente (para saldo). Ajusta si tu negocio usa otra referencia.
        $compra = Compra::where('cliente_id', $cliente->idCli)->latest('id')->first();
        $saldoAnterior = $compra ? (float)$compra->saldo : 0;

        // 3) Cálculos
        $mensualidad = (float)$request->mensualidad;
        $n = (int)$request->mensualidades;
        $subtotal = $mensualidad * $n;

        $fechaPago = Carbon::parse($request->fecha_pago);
        $fechaVenc = Carbon::parse($request->fecha_vencimiento);

        // ✅ Multa 10% si paga tarde
        $pagoATiempo = $fechaPago->lte($fechaVenc);
        $multa = $pagoATiempo ? 0 : round($subtotal * 0.10, 2);

        $total = $subtotal + $multa;

        // ✅ saldo nuevo: solo subtotal reduce deuda
        $saldoNuevo = max($saldoAnterior - $subtotal, 0);

        // 4) Registrar movimiento como ingreso (en caja sistema)
        $cajaId = $this->cajaSistemaId();

        $descripcion = "COBRO | Cliente: {$cliente->Nombre} | Tel: {$cliente->telefono} | Dir: {$cliente->direccion} "
            . "| Mensualidad: $" . number_format($mensualidad, 2)
            . " | Meses: {$n} | Subtotal: $" . number_format($subtotal, 2)
            . " | Multa: $" . number_format($multa, 2)
            . " | Total: $" . number_format($total, 2)
            . " | Tipo: {$request->tipo}"
            . " | Vence: {$fechaVenc->toDateString()} | Pagó: {$fechaPago->toDateString()}";

        if (!empty($request->notas)) {
            $descripcion .= " | Notas: {$request->notas}";
        }

        DB::beginTransaction();
        try {
            MovimientoCaja::create([
                'caja_id' => $cajaId,
                'user_id' => Auth::id(),
                'tipo' => 'ingreso',
                'descripcion' => $descripcion,
                'monto' => $total,
                'metodo_pago' => $request->metodo_pago === 'efectivo' ? 'Efectivo' : 'Transferencia',
            ]);

            // 5) Actualizar saldo en compras (si existe)
            if ($compra) {
                $compra->saldo = $saldoNuevo;

                // si tu compra tiene mensualidades restantes, se descuenta
                if (!is_null($compra->mensualidades)) {
                    $compra->mensualidades = max(((int)$compra->mensualidades) - $n, 0);
                }

                $compra->save();
            }

            DB::commit();

            return redirect()->route('cajas.index')
                ->with('success', "Cobro registrado. Total: $" . number_format($total, 2) . " | Saldo nuevo: $" . number_format($saldoNuevo, 2));

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error("Error registrarCobro: " . $e->getMessage());
            return redirect()->route('cajas.index')->with('error', 'Error al registrar el cobro. Revisa los logs.');
        }
    }

    // ===================================================================
    // ✅ Si ya NO quieres movimientos manuales, puedes borrar esto.
    // Si sí lo quieres, lo adaptamos sin caja abierta.
    // ===================================================================
    public function registrarMovimiento(Request $request)
    {
        $request->validate([
            'tipo' => 'required|in:ingreso,egreso',
            'monto' => 'required|numeric|min:0.01',
            'descripcion' => 'required|string|max:255',
        ]);

        $cajaId = $this->cajaSistemaId();

        MovimientoCaja::create([
            'caja_id' => $cajaId,
            'user_id' => Auth::id(),
            'tipo' => $request->tipo,
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'metodo_pago' => 'Manual',
        ]);

        return redirect()->route('cajas.index')->with('success', 'Movimiento registrado exitosamente.');
    }
}

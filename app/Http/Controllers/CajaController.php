<?php

namespace App\Http\Controllers;

use App\Models\Caja;
use App\Models\Venta; // <-- Asegurar que Venta esté importado
use App\Models\MovimientoCaja; // <-- ¡IMPORTANTE AÑADIR ESTE!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CajaController extends Controller
{
    /**
     * Muestra el estado actual de la caja.
     */
    public function index()
    {
        // Obtener la caja abierta por el usuario actual
        $cajaAbierta = Caja::with('user')
            ->where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        $movimientos = collect();
        $saldoActual = 0;
        $ventasEfectivo = 0; // <-- NUEVO: Inicializar variable para ventas en efectivo
        $saldoMovimientos = 0; // <-- AÑADIDO: Inicializar saldoMovimientos

        if ($cajaAbierta) {
            // Obtener sus movimientos manuales
            $movimientos = MovimientoCaja::where('caja_id', $cajaAbierta->id)
                ->with('user') // <-- AÑADIDO: Cargar usuario aquí
                ->orderBy('created_at', 'desc')
                ->get();

            // Calcular el saldo de movimientos manuales
            $saldoMovimientos = $movimientos->sum(function ($mov) {
                // Asumiendo que 'ingreso' suma y 'egreso' (u otro tipo) resta
                return $mov->tipo === 'ingreso' ? $mov->monto : -$mov->monto;
            });
            
            // <-- NUEVO: Calcular Ventas en Efectivo desde la apertura de esta caja -->
            $ventasEfectivo = Venta::where('user_id', Auth::id()) // Ventas del usuario actual
                                    ->where('metodo_pago', 'efectivo') // SOLO EFECTIVO
                                    ->where('fecha_hora', '>=', $cajaAbierta->fecha_hora_apertura) // Desde que abrió caja
                                    // ->where('caja_id', $cajaAbierta->id) // Si tuvieras caja_id en Ventas, sería más preciso
                                    ->sum('total');
            
            // <-- MODIFICADO: Añadir ventas en efectivo al saldo actual -->
            $saldoActual = $cajaAbierta->saldo_inicial + $saldoMovimientos + $ventasEfectivo;
        }

        // <-- MODIFICADO: Pasar $ventasEfectivo y $saldoMovimientos a la vista -->
        return view('cajas.index', compact(
            'cajaAbierta', 
            'movimientos', 
            'saldoActual', 
            'ventasEfectivo', 
            'saldoMovimientos' // Pasar también el total de movimientos
        ));
    }

    /**
     * Abre una nueva caja (Sin cambios necesarios aquí).
     */
    public function abrirCaja(Request $request)
    {
        $request->validate([
            'saldo_inicial' => 'required|numeric|min:0',
        ]);

        if (Caja::where('user_id', Auth::id())->where('estado', 'abierta')->exists()) {
            return redirect()->route('cajas.index')->with('error', 'Ya tienes una caja abierta. Cierra el turno anterior primero.');
        }

        Caja::create([
            'user_id' => Auth::id(),
            'fecha_hora_apertura' => now(),
            'saldo_inicial' => $request->saldo_inicial,
            'estado' => 'abierta',
        ]);

        return redirect()->route('cajas.index')->with('success', 'Caja abierta exitosamente con saldo inicial de $' . number_format($request->saldo_inicial, 2));
    }

    /**
     * Cierra la caja abierta (Modificado para incluir ventas y crear movimiento).
     */
    public function cerrarCaja(Request $request)
    {
        $caja = Caja::where('user_id', Auth::id())
            ->where('estado', 'abierta')
            ->first();

        if (!$caja) {
            return redirect()->route('cajas.index')->with('error', 'No se encontró ninguna caja abierta para cerrar.');
        }

        // <-- MODIFICADO: Usar transacción para asegurar atomicidad -->
        DB::beginTransaction();
        
        try {
            // Calcular saldo de movimientos manuales
            // 1. Sumar todos los ingresos
            $ingresosManuales = MovimientoCaja::where('caja_id', $caja->id)
                ->where('tipo', 'ingreso')
                ->sum('monto');

            // 2. Sumar todos los egresos (todo lo que NO sea 'ingreso')
            $egresosManuales = MovimientoCaja::where('caja_id', $caja->id)
                ->where('tipo', '!=', 'ingreso')
                ->sum('monto');

            // 3. Calcular el saldo final de movimientos
            $saldoMovimientos = $ingresosManuales - $egresosManuales;
        
            // <-- TODO EL CÓDIGO SIGUIENTE AHORA VA DENTRO DEL 'try' -->

            // <-- MODIFICADO: Calcular Ventas en Efectivo para ESTA caja específica -->
            $ventasEfectivo = Venta::where('user_id', Auth::id()) 
                ->where('metodo_pago', 'efectivo')
                ->where('fecha_hora', '>=', $caja->fecha_hora_apertura) // Desde apertura
                ->where('fecha_hora', '<=', now()) // Hasta el cierre
                // ->where('caja_id', $caja->id) // Si tuvieras caja_id en Ventas
                ->sum('total');

            // <-- MODIFICADO: Calcular saldo final incluyendo ventas en efectivo -->
            $saldoCalculadoCierre = $caja->saldo_inicial + $saldoMovimientos + $ventasEfectivo;

            // <-- NUEVO: Registrar las ventas en efectivo como un movimiento de caja al cerrar -->
            if ($ventasEfectivo > 0) {
                MovimientoCaja::create([
                    'caja_id' => $caja->id,
                    'user_id' => Auth::id(), // <-- AÑADIDO: Guardar quién hizo el movimiento
                    'tipo' => 'ingreso',
                    'descripcion' => 'Ventas en Efectivo del Turno',
                    'monto' => $ventasEfectivo,
                    'metodo_pago' => 'sistema', // Indicar que es automático
                    'created_at' => now(), // Asegurar timestamp
                    'updated_at' => now(),
                ]);
            }

            // Actualizar el registro de caja
            $caja->update([
                'fecha_hora_cierre' => now(),
                'saldo_final' => $saldoCalculadoCierre, // Usar el saldo calculado que incluye ventas
                'estado' => 'cerrada',
            ]);

            DB::commit(); // Confirmar transacción

            return redirect()->route('cajas.index')->with('success', 'Caja cerrada exitosamente. Saldo final calculado: $' . number_format($saldoCalculadoCierre, 2));

        } catch (\Exception $e) {
            DB::rollBack(); // Revertir en caso de error
            \Log::error("Error al cerrar caja: " . $e->getMessage()); // Loguear el error
            return redirect()->route('cajas.index')->with('error', 'Ocurrió un error al cerrar la caja. Verifique los logs.');
        }
    }

    // ===================================================================
    // ============= ¡NUEVA FUNCIÓN PARA MOVIMIENTOS MANUALES! =============
    // ===================================================================
    /**
     * Registra un movimiento manual (ingreso o egreso) en la caja abierta.
     */
    public function registrarMovimiento(Request $request)
    {
        $request->validate([
            'caja_id' => 'required|exists:cajas,id',
            'tipo' => 'required|in:ingreso,egreso', // Validar 'ingreso' o 'egreso'
            'monto' => 'required|numeric|min:0.01', // Monto siempre positivo
            'descripcion' => 'required|string|max:255',
        ]);

        // Doble chequeo: la caja debe estar abierta y pertenecer al usuario
        $cajaAbierta = Caja::where('id', $request->caja_id)
                           ->where('user_id', Auth::id())
                           ->where('estado', 'abierta')
                           ->first();
                           
        if (!$cajaAbierta) {
            return redirect()->route('cajas.index')->with('error', 'Error: No se encontró tu caja abierta.');
        }

        // Guardamos el movimiento usando tu estructura de tabla
        MovimientoCaja::create([
            'caja_id' => $cajaAbierta->id,
            'user_id' => Auth::id(), // Guardar quién lo hizo
            'tipo' => $request->tipo, // 'ingreso' o 'egreso'
            'descripcion' => $request->descripcion,
            'monto' => $request->monto,
            'metodo_pago' => 'Efectivo (Manual)', // Método de pago descriptivo
        ]);
        
        return redirect()->route('cajas.index')->with('success', 'Movimiento manual registrado exitosamente.');
    }
}
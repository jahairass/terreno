<?php

namespace App\Http\Controllers;

use App\Models\Compra;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class CompraController extends Controller
{
    /**
     * Muestra una lista de todos los registros de compras.
     */
    public function index()
    {
        $compras = Compra::with('proveedor')->orderBy('created_at', 'desc')->get();
        // El middleware 'permiso:compras,mostrar' ya protege esta ruta
        return view('compras.index', compact('compras'));
    }

    /**
     * Muestra el formulario para crear un nuevo registro de compra.
     */
    public function create()
    {
        $proveedores = Proveedor::all();
        return view('compras.create', compact('proveedores'));
    }

    /**
     * Almacena un nuevo registro de compra.
     */
    public function store(Request $request)
    {
        // El middleware 'permiso:compras,alta' ya protege esta función
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'descripcion' => 'nullable|string|max:255',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,credito,transferencia',
            'total' => 'required|numeric|min:0.01',
        ]);

        Compra::create($request->all());

        // NOTA: Aquí iría la lógica para AUMENTAR el stock en el inventario.
        // Lo implementaremos en un paso posterior si se necesita detalle de compra.

        return redirect()->route('compras.index')->with('success', 'Registro de compra completado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un registro de compra.
     */
    public function edit(Compra $compra)
    {
        $proveedores = Proveedor::all();
        return view('compras.edit', compact('compra', 'proveedores'));
    }

    /**
     * Actualiza un registro de compra.
     */
    public function update(Request $request, Compra $compra)
    {
        // El middleware 'permiso:compras,editar' ya protege esta función
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'descripcion' => 'nullable|string|max:255',
            'metodo_pago' => 'required|string|in:efectivo,tarjeta,credito,transferencia',
            'total' => 'required|numeric|min:0.01',
        ]);

        $compra->update($request->all());

        return redirect()->route('compras.index')->with('success', 'Registro de compra actualizado exitosamente.');
    }

    /**
     * Elimina un registro de compra.
     */
    public function destroy(Compra $compra)
    {
        // El middleware 'permiso:compras,eliminar' ya protege esta función
        // NOTA: La lógica para AJUSTAR el stock tras la eliminación iría aquí.
        
        $compra->delete();
        return redirect()->route('compras.index')->with('success', 'Registro de compra eliminado exitosamente.');
    }
}
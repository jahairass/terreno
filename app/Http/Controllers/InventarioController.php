<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Muestra la lista de terrenos (productos).
     */
    public function index()
    {
        // Obtener productos con su categoría
        $productos = Producto::with('categoria')->get();

        return view('inventario.index', compact('productos'));
    }

    /**
     * Muestra el formulario para editar un terreno.
     */
    public function edit(Producto $producto)
    {
        return view('inventario.edit', compact('producto'));
    }

    /**
     * Actualiza el estado y nivel del terreno.
     */
    public function update(Request $request, Producto $producto)
    {
        // Validación SOLO de estado y nivel
        $request->validate([
            'estado' => 'required|in:disponible,proceso,vendido',
            'nivel'  => 'required|in:basico,plus,premium',
        ]);

        // Actualizar directamente el producto
        $producto->update([
            'estado' => $request->estado,
            'nivel'  => $request->nivel,
        ]);

        return redirect()
            ->route('inventario.index')
            ->with('success', 'Terreno "' . $producto->nombre . '" actualizado correctamente.');
    }
}

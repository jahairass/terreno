<?php

namespace App\Http\Controllers;

use App\Models\Inventario; // <- ahora es tabla terrenos
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * /inventario -> Mapa de terrenos (MISMA RUTA)
     */
    public function index()
    {
        $terrenos = Inventario::all();

        $maxFila = (int) ($terrenos->max('fila') ?? 1);
        $maxCol  = (int) ($terrenos->max('columna') ?? 1);

        // Indexar por celda "fila-columna"
        $porCelda = $terrenos->keyBy(fn ($t) => $t->fila . '-' . $t->columna);

        return view('inventario.index', compact('maxFila', 'maxCol', 'porCelda'));
    }

    /**
     * Formulario para editar terreno
     * Ruta: GET /inventario/{terreno}/edit
     */
    public function edit(Inventario $terreno)
    {
        return view('inventario.edit', compact('terreno'));
    }

    /**
     * Guardar cambios de terreno
     * Ruta: PUT /inventario/{terreno}
     */
    public function update(Request $request, Inventario $terreno)
    {
        $request->validate([
            'codigo'   => 'required|string|max:255|unique:terrenos,codigo,' . $terreno->id,
            'alcaldia' => 'nullable|string|max:255',
            'ubicacion'=> 'nullable|string|max:255',
            'precio'   => 'required|numeric|min:0',
            'estado'   => 'required|in:disponible,en_proceso,vendido',
            'fila'     => 'required|integer|min:1',
            'columna'  => 'required|integer|min:1',
        ]);

        // Evitar que 2 terrenos usen la misma celda (fila/col)
        $ocupada = Inventario::where('fila', $request->fila)
            ->where('columna', $request->columna)
            ->where('id', '!=', $terreno->id)
            ->exists();

        if ($ocupada) {
            return back()
                ->withErrors(['columna' => 'Ya existe un terreno en esa fila/columna.'])
                ->withInput();
        }

        $terreno->update($request->only([
            'codigo','alcaldia','ubicacion','precio','estado','fila','columna'
        ]));

        return redirect()->route('inventario.index')->with('success', 'Terreno actualizado.');
    }
}

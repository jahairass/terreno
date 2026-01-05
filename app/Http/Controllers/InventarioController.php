<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * /inventario
     * Lista terrenos + barra de clientes (chips) + filtro por cliente
     */
    public function index(Request $request)
    {
        $clienteFiltro = $request->get('cliente'); // ?cliente=...

        // Para los chips (lista única de clientes)
        $clientes = Inventario::query()
            ->whereNotNull('cliente')
            ->where('cliente', '!=', '')
            ->select('cliente')
            ->distinct()
            ->orderBy('cliente')
            ->pluck('cliente');

        // Terrenos (filtrados si se selecciona un cliente)
        $query = Inventario::query()->orderBy('id', 'asc');

        if (!empty($clienteFiltro)) {
            $query->where('cliente', $clienteFiltro);
        }

        $terrenos = $query->get();

        return view('inventario.index', compact('terrenos', 'clientes', 'clienteFiltro'));
    }

    /**
     * Formulario para crear
     */
    public function create()
    {
        return view('inventario.create');
    }

    /**
     * Guardar nuevo terreno
     * fila/columna internas (no se muestran)
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente'   => 'required|string|max:255', // ✅ ahora es cliente
            'alcaldia'  => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'precio'    => 'required|numeric|min:0',
            'estado'    => 'required|in:disponible,reservado,vendido',
        ]);

        // Asignación interna automática
        $total = Inventario::count();
        $colsPorFila = 6;

        $fila = intdiv($total, $colsPorFila) + 1;
        $columna = ($total % $colsPorFila) + 1;

        Inventario::create([
            'cliente'   => $request->cliente,  // ✅ ahora es cliente
            'alcaldia'  => $request->alcaldia,
            'ubicacion' => $request->ubicacion,
            'precio'    => $request->precio,
            'estado'    => $request->estado,
            'fila'      => $fila,
            'columna'   => $columna,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Terreno agregado correctamente.');
    }

    /**
     * Formulario editar
     */
    public function edit(Inventario $terreno)
    {
        return view('inventario.edit', compact('terreno'));
    }

    /**
     * Actualizar (no toca fila/columna)
     */
    public function update(Request $request, Inventario $terreno)
    {
        $request->validate([
            'cliente'   => 'required|string|max:255', // ✅ ahora es cliente
            'alcaldia'  => 'nullable|string|max:255',
            'ubicacion' => 'nullable|string|max:255',
            'precio'    => 'required|numeric|min:0',
            'estado'    => 'required|in:disponible,reservado,vendido',
        ]);

        $terreno->update($request->only([
            'cliente',
            'alcaldia',
            'ubicacion',
            'precio',
            'estado',
        ]));

        return redirect()->route('inventario.index')->with('success', 'Terreno actualizado.');
    }

    /**
     * Eliminar
     */
    public function destroy(Inventario $terreno)
    {
        $terreno->delete();
        return redirect()->route('inventario.index')->with('success', 'Terreno eliminado correctamente.');
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\Categoria; // <-- ¡ESTA ES LA LÍNEA CRÍTICA QUE SOLUCIONA EL ERROR!
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    /**
     * Muestra una lista de todas las categorías.
     */
    public function index()
    {
        // El error 500 se resuelve porque ahora Laravel encuentra la clase Categoria
        $categorias = Categoria::all(); 
        return view('categorias.index', compact('categorias'));
    }

    /**
     * Muestra el formulario para crear una nueva categoría.
     */
    public function create()
    {
        return view('categorias.create');
    }

    /**
     * Almacena una nueva categoría.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Asegura que el nombre sea único en la tabla 'categorias'
            'nombre' => ['required', 'string', 'max:255', 'unique:categorias,nombre'],
        ]);

        Categoria::create(['nombre' => $request->nombre]);

        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    /**
     * Muestra el formulario para editar una categoría existente.
     */
    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    /**
     * Actualiza una categoría.
     */
    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Asegura que el nombre sea único, ignorando la categoría actual
                Rule::unique('categorias', 'nombre')->ignore($categoria->id),
            ],
        ]);

        $categoria->update(['nombre' => $request->nombre]);

        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    /**
     * Elimina una categoría.
     */
    public function destroy(Categoria $categoria)
    {
        // Manejo de la clave foránea si hay productos asociados
        try {
            $categoria->delete();
            return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('categorias.index')->with('error', 'No se puede eliminar la categoría porque tiene productos asociados. Reasigna o elimina los productos primero.');
        }
    }
}
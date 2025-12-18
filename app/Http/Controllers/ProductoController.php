<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Inventario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <--- Referencia restaurada
use Illuminate\Validation\Rule;   // <--- Referencia restaurada
use Illuminate\Support\Facades\Storage; // Referencia para la imagen

class ProductoController extends Controller
{
    /**
     * Muestra la lista de productos con su categoría y stock.
     */
    public function index()
    {
        // Cargar las relaciones para evitar consultas N+1
        $productos = Producto::with(['categoria', 'inventario'])->get();
        return view('productos.index', compact('productos'));
    }

    /**
     * Muestra el formulario de creación.
     */
    public function create()
    {
        $categorias = Categoria::all();
        // Se mantiene solo $categorias como en tu código original
        return view('productos.create', compact('categorias'));
    }

    /**
     * Almacena un nuevo producto y su registro inicial de inventario.
     */
    public function store(Request $request)
    {
        // ***** CAMBIO: Añadir validación para la imagen *****
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB max
            // Campos de Inventario
            'stock_inicial' => 'required|integer|min:0',
            'cantidad_minima' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();

        try {
            // ***** CAMBIO: Preparar datos y manejar imagen *****
            // (Se quita 'imagen' de $request->only para manejarla por separado)
            $datosProducto = $request->only(['categoria_id', 'nombre', 'descripcion', 'precio']);

            if ($request->hasFile('imagen')) {
                // Guarda la imagen en 'storage/app/public/productos'
                $path = $request->file('imagen')->store('productos', 'public');
                $datosProducto['imagen'] = $path; // Añadir la ruta de la imagen
            }
            // Si no se sube archivo, no se añade la clave 'imagen'

            // 1. Crear el Producto (con o sin la clave 'imagen')
            $producto = Producto::create($datosProducto);

            // 2. Crear el registro de Inventario inicial (lógica original)
            Inventario::create([
                'producto_id' => $producto->id,
                'stock' => $request->stock_inicial,
                'cantidad_minima' => $request->cantidad_minima,
                'cantidad_maxima' => 99999, 
            ]);

            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Producto e Inventario inicial creados exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear el producto: ' . $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para editar el producto y su inventario.
     */
    public function edit(Producto $producto)
    {
        $categorias = Categoria::all();
        // Cargar el inventario relacionado
        $producto->load('inventario'); 
        // Se mantiene solo 'producto' y 'categorias'
        return view('productos.edit', compact('producto', 'categorias'));
    }

    /**
     * Actualiza el producto y su inventario.
     */
    public function update(Request $request, Producto $producto)
    {
        // ***** CAMBIO: Añadir validación para la nueva imagen *****
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => ['required', 'string', 'max:255', Rule::unique('productos')->ignore($producto->id)],
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0.01',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // 2MB max
            'cantidad_minima' => 'required|integer|min:0',
            // 'stock' => 'required|integer|min:0', // Tu formulario edit deshabilita este campo,
                                                      // pero si lo habilitas, descomenta esta validación.
        ]);

        DB::beginTransaction();

        try {
            // ***** CAMBIO: Preparar datos y manejar actualización de imagen *****
            // (Se quita 'imagen' de $request->only)
            $datosProducto = $request->only(['categoria_id', 'nombre', 'descripcion', 'precio']);

            if ($request->hasFile('imagen')) {
                // 1. Borrar la imagen antigua si existe
                if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                    Storage::disk('public')->delete($producto->imagen);
                }
                
                // 2. Guardar la nueva imagen
                $path = $request->file('imagen')->store('productos', 'public');
                $datosProducto['imagen'] = $path; // Añadir la nueva ruta
            }
            // Si no se sube archivo, $datosProducto no incluye 'imagen' y la BBDD no se toca.

            // 1. Actualizar Producto
            $producto->update($datosProducto);

            // 2. Actualizar Inventario (lógica original)
            // (Tu formulario edit solo envía 'cantidad_minima',
            // el campo 'stock' está deshabilitado. Si lo habilitas, añade 'stock' => $request->stock aquí)
            $producto->inventario->update([
                // 'stock' => $request->stock, // Descomenta si habilitas el campo 'stock'
                'cantidad_minima' => $request->cantidad_minima,
            ]);
            
            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Producto y stock actualizados exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el producto: ' . $e->getMessage());
        }
    }

    /**
     * Elimina el producto y su registro de inventario asociado.
     */
    public function destroy(Producto $producto)
    {
        DB::beginTransaction();
        try {
            // ***** CAMBIO: Eliminar la imagen del almacenamiento *****
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }

            // Lógica original para eliminar inventario
            if ($producto->inventario) {
                $producto->inventario->delete();
            }

            $producto->delete();
            DB::commit();

            return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('productos.index')->with('error', 'Ocurrió un error al eliminar el producto.');
        }
    }
}
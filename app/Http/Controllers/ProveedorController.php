<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    /**
     * Muestra una lista de todos los proveedores.
     */
    public function index()
    {
        $proveedores = Proveedor::all();
        // El middleware 'permiso:proveedores,mostrar' ya protege esta ruta
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Muestra el formulario para crear un nuevo proveedor.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Almacena un nuevo proveedor.
     */
    public function store(Request $request)
    {
        // El middleware 'permiso:proveedores,alta' ya protege esta ruta
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'correo' => 'nullable|email|max:255|unique:proveedores,correo',
        ]);

        Proveedor::create($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor registrado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un proveedor.
     */
    public function edit(Proveedor $proveedore)
    {
        // El middleware 'permiso:proveedores,editar' ya protege esta ruta
        return view('proveedores.edit', compact('proveedore'));
    }

    /**
     * Actualiza un proveedor.
     */
    public function update(Request $request, Proveedor $proveedore)
    {
        // El middleware 'permiso:proveedores,editar' ya protege esta ruta
        $request->validate([
            'nombre' => 'required|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'correo' => ['nullable', 'email', 'max:255', Rule::unique('proveedores', 'correo')->ignore($proveedore->id)],
        ]);

        $proveedore->update($request->all());

        return redirect()->route('proveedores.index')->with('success', 'Proveedor actualizado exitosamente.');
    }

    /**
     * Elimina un proveedor.
     */
    public function destroy(Proveedor $proveedore)
    {
        // El middleware 'permiso:proveedores,eliminar' ya protege esta ruta
        try {
            $proveedore->delete();
            return redirect()->route('proveedores.index')->with('success', 'Proveedor eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Esto sucede si el proveedor ya tiene compras asociadas
            return redirect()->route('proveedores.index')->with('error', 'No se puede eliminar el proveedor porque tiene registros de compras asociados.');
        }
    }
}
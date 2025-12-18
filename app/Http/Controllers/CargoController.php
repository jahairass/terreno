<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Necesario para la validación 'unique'

class CargoController extends Controller
{
    /**
     * Muestra la lista de cargos.
     */
    public function index()
    {
        $cargos = Cargo::all();
        return view('cargos.index', compact('cargos'));
    }

    /**
     * Muestra el formulario para crear un nuevo cargo.
     */
    public function create()
    {
        return view('cargos.create');
    }

    /**
     * Almacena un nuevo cargo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            // Asegura que el nombre sea requerido y único en la tabla 'cargos'
            'nombre' => ['required', 'string', 'max:255', 'unique:cargos,nombre'],
        ]);

        Cargo::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('cargos.index')->with('success', 'Cargo creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un cargo existente.
     */
    public function edit(Cargo $cargo)
    {
        return view('cargos.edit', compact('cargo'));
    }

    /**
     * Actualiza un cargo en la base de datos.
     */
    public function update(Request $request, Cargo $cargo)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                // Asegura que el nombre sea único, excepto para el cargo actual
                Rule::unique('cargos', 'nombre')->ignore($cargo->id),
            ],
        ]);

        $cargo->update(['nombre' => $request->nombre]);

        return redirect()->route('cargos.index')->with('success', 'Cargo actualizado exitosamente.');
    }

    /**
     * Elimina un cargo (con protección para el Super Administrador).
     */
    public function destroy(Cargo $cargo)
    {
        // Protección: Evitar eliminar el cargo de Super Administrador (ID 1)
        if ($cargo->id === 1) {
            return redirect()->route('cargos.index')->with('error', 'El cargo de Super Administrador no puede ser eliminado.');
        }

        // Ya que users.cargo_id tiene ON DELETE SET NULL, no habrá problemas de FK si hay usuarios
        $cargo->delete();

        return redirect()->route('cargos.index')->with('success', 'Cargo eliminado exitosamente.');
    }
}
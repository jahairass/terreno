<?php

namespace App\Http\Controllers;

use App\Models\Cargo;
use App\Models\Modulo;
use App\Models\Permiso;
use Illuminate\Http\Request;

class PermisoController extends Controller
{
    /**
     * Muestra la matriz de permisos para un cargo específico.
     */
    public function index(Cargo $cargo)
    {
        // 1. Protección: El Super Administrador (ID 1) siempre tiene todos los permisos
        if ($cargo->id === 1) {
            return redirect()->route('cargos.index')->with('warning', 'El Super Administrador tiene todos los permisos por defecto y no pueden ser modificados.');
        }

        // 2. Obtener todos los módulos disponibles
        $modulos = Modulo::all();

        // 3. Obtener los permisos existentes para este cargo, indexados por modulo_id
        $permisosActuales = $cargo->permisos->keyBy('modulo_id');

        return view('permisos.index', compact('cargo', 'modulos', 'permisosActuales'));
    }

    /**
     * Actualiza la matriz de permisos para el cargo.
     */
    public function update(Request $request, Cargo $cargo)
    {
        // 1. Protección contra manipulación del Super Administrador
        if ($cargo->id === 1) {
            return redirect()->route('cargos.permisos.index', $cargo)->with('error', 'No se pueden modificar los permisos del Super Administrador.');
        }

        $datos = $request->except('_token', '_method');
        $modulos = Modulo::pluck('id')->toArray();

        foreach ($modulos as $modulo_id) {
            // Genera el array de datos con 0 (falso) si no está marcado en el formulario
            $permisoData = [
                'mostrar' => isset($datos['mostrar'][$modulo_id]) ? 1 : 0,
                'alta'    => isset($datos['alta'][$modulo_id]) ? 1 : 0,
                'detalle' => isset($datos['detalle'][$modulo_id]) ? 1 : 0,
                'editar'  => isset($datos['editar'][$modulo_id]) ? 1 : 0,
                'eliminar' => isset($datos['eliminar'][$modulo_id]) ? 1 : 0,
            ];

            // Actualizar o crear el permiso
            Permiso::updateOrCreate(
                [
                    'cargo_id' => $cargo->id,
                    'modulo_id' => $modulo_id
                ],
                $permisoData
            );
        }

        return redirect()->route('cargos.permisos.index', $cargo)->with('success', 'Permisos del cargo ' . $cargo->nombre . ' actualizados exitosamente.');
    }
}
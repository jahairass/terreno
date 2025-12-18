<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Cargo;
use App\Models\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class EmpleadoController extends Controller
{
    /**
     * Muestra una lista de todos los empleados y sus cargos.
     */
    public function index()
    {
        $users = User::with(['cargo', 'empleado'])->get();
        return view('empleados.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo empleado.
     */
    public function create()
    {
        $cargos = Cargo::all();
        return view('empleados.create', compact('cargos'));
    }

    /**
     * Almacena un nuevo empleado y usuario en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'cargo_id' => 'required|exists:cargos,id',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // 1. Crear el registro en la tabla 'users'
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'cargo_id' => $request->cargo_id,
            ]);

            // 2. Crear el registro en la tabla 'empleados' usando el ID del usuario recién creado
            Empleado::create([
                'idUserFK' => $user->id, // ESTO AHORA FUNCIONA GRACIAS A LA CORRECCIÓN EN EL MODELO EMPLEADO
                'telefono' => $request->telefono,
                'direccion' => $request->direccion,
            ]);

            DB::commit();

            return redirect()->route('empleados.index')->with('success', 'Empleado creado exitosamente y usuario activado.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Retornar un error detallado para el debugging
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al crear el empleado: ' . $e->getMessage());
        }
    }

    /**
     * Muestra los datos de un empleado específico.
     */
    public function show(User $empleado)
    {
        $empleado->load('cargo', 'empleado');
        return view('empleados.show', compact('empleado'));
    }

    /**
     * Muestra el formulario para editar un empleado.
     */
    public function edit(User $empleado)
    {
        $cargos = Cargo::all();
        $empleado->load('empleado');
        return view('empleados.edit', compact('empleado', 'cargos'));
    }

    /**
     * Actualiza un empleado en la base de datos.
     */
    public function update(Request $request, User $empleado)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($empleado->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'cargo_id' => 'required|exists:cargos,id',
            'telefono' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'cargo_id' => $request->cargo_id,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $empleado->update($userData);

            // Asegurar que el registro de empleado exista antes de actualizarlo
            if ($empleado->empleado) {
                 $empleado->empleado->update([
                    'telefono' => $request->telefono,
                    'direccion' => $request->direccion,
                ]);
            }
           

            DB::commit();

            return redirect()->route('empleados.index')->with('success', 'Empleado actualizado exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el empleado.');
        }
    }

    /**
     * Elimina un empleado y su usuario asociado.
     */
    public function destroy(User $empleado)
    {
        if (Auth::id() === $empleado->id) {
            return redirect()->route('empleados.index')->with('error', 'No puedes eliminar tu propia cuenta mientras estás logueado.');
        }

        if ($empleado->id === 1) {
            return redirect()->route('empleados.index')->with('error', 'El Super Administrador no puede ser eliminado.');
        }
        
        DB::beginTransaction();
        try {
            $empleado->delete(); // Gracias a ON DELETE CASCADE en la BD, Empleado se borra automáticamente
            DB::commit();

            return redirect()->route('empleados.index')->with('success', 'Empleado y usuario eliminados exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('empleados.index')->with('error', 'Ocurrió un error al eliminar el empleado.');
        }
    }
}
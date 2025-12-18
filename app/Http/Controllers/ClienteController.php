<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:255',
            'correo' => 'nullable|string|max:255',
            'identificacion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255'
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'Nombre' => ['required', 'string', 'max:255', Rule::unique('clientes', 'Nombre')->ignore($cliente->idCli, 'idCli')],
            'telefono' => 'nullable|string|max:255',
            'correo' => 'nullable|string|max:255',
            'identificacion' => 'nullable|string|max:255',
            'direccion' => 'nullable|string|max:255'
        ]);

        $cliente->update($request->all());
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        try {
            $cliente->delete();
            return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
        } catch (\Illuminate\Database\QueryException $e) {
            return redirect()->route('clientes.index')->with('error', 'No se puede eliminar el cliente porque tiene ventas registradas.');
        }
    }
}

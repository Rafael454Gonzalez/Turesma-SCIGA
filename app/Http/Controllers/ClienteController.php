<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $clientes = Cliente::withCount('facturas')->orderBy('razon_social')->paginate($perPage)->withQueryString();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        $this->authorizePermission('crear-clientes');
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $this->authorizePermission('crear-clientes');
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|max:20|unique:clientes,ruc',
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
        ]);

        $validated['activo'] = $request->boolean('activo');

        Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    public function edit(Cliente $cliente)
    {
        $this->authorizePermission('editar-clientes');
        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $this->authorizePermission('editar-clientes');
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
            'ruc' => 'required|string|max:20|unique:clientes,ruc,' . $cliente->id,
            'contacto' => 'nullable|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $cliente->update($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Cliente $cliente)
    {
        $this->authorizePermission('editar-clientes');
        $cliente->delete();
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}

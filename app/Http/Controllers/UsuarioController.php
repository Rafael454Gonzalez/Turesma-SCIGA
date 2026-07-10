<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Socio;
use App\Models\Seguridad\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('rolPrincipal', 'socio')->orderBy('name')->get();
        return view('usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $this->authorizePermission('crear-usuarios');
        $roles = Role::where('activo', true)->orderBy('nombre')->get();
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        return view('usuarios.create', compact('roles', 'socios'));
    }

    public function store(Request $request)
    {
        $this->authorizePermission('crear-usuarios');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
            'socio_id' => 'nullable|exists:socios,id',
            'activo' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit(User $usuario)
    {
        $this->authorizePermission('editar-usuarios');
        $roles = Role::where('activo', true)->orderBy('nombre')->get();
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        return view('usuarios.edit', compact('usuario', 'roles', 'socios'));
    }

    public function update(Request $request, User $usuario)
    {
        $this->authorizePermission('editar-usuarios');
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $usuario->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'nullable|exists:roles,id',
            'socio_id' => 'nullable|exists:socios,id',
            'activo' => 'boolean',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(User $usuario)
    {
        $this->authorizePermission('eliminar-usuarios');
        $usuario->delete();
        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}

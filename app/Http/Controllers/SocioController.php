<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Socio;
use Illuminate\Http\Request;

class SocioController extends Controller
{
    public function index()
    {
        $socios = Socio::withCount('facturas', 'aportes')->orderBy('nombres')->get();
        return view('socios.index', compact('socios'));
    }

    public function create()
    {
        return view('socios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'identificacion' => 'required|string|max:50|unique:socios,identificacion',
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'cuota_mensual_base' => 'nullable|numeric|min:0',
            'porcentaje_participacion' => 'nullable|numeric|min:0|max:100',
            'tipo_socio' => 'required|in:socio,colaborador',
        ]);

        $validated['activo'] = $request->boolean('activo');

        Socio::create($validated);

        return redirect()->route('socios.index')->with('success', 'Socio creado exitosamente.');
    }

    public function edit(Socio $socio)
    {
        return view('socios.edit', compact('socio'));
    }

    public function update(Request $request, Socio $socio)
    {
        $validated = $request->validate([
            'nombres' => 'required|string|max:255',
            'identificacion' => 'required|string|max:50|unique:socios,identificacion,' . $socio->id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'direccion' => 'nullable|string|max:500',
            'cuota_mensual_base' => 'nullable|numeric|min:0',
            'porcentaje_participacion' => 'nullable|numeric|min:0|max:100',
            'tipo_socio' => 'required|in:socio,colaborador',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $socio->update($validated);

        return redirect()->route('socios.index')->with('success', 'Socio actualizado exitosamente.');
    }

    public function destroy(Socio $socio)
    {
        $socio->delete();
        return redirect()->route('socios.index')->with('success', 'Socio eliminado exitosamente.');
    }
}

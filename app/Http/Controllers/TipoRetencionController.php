<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\TipoRetencion;
use Illuminate\Http\Request;

class TipoRetencionController extends Controller
{
    public function index()
    {
        $tipos = TipoRetencion::orderBy('nombre')->get();
        return view('tipos_retencion.index', compact('tipos'));
    }

    public function create()
    {
        $this->authorizePermission('crear-facturas');
        return view('tipos_retencion.create');
    }

    public function store(Request $request)
    {
        $this->authorizePermission('crear-facturas');
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_retencion,nombre',
            'slug' => 'nullable|string|max:255|unique:tipos_retencion,slug',
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $tipo = TipoRetencion::create($validated);

        if ($request->wantsJson()) {
            return response()->json($tipo->only('id', 'nombre', 'porcentaje'));
        }

        return redirect()->route('tipos-retencion.index')->with('success', 'Tipo de retención creado exitosamente.');
    }

    public function edit(TipoRetencion $tipos_retencion)
    {
        $this->authorizePermission('crear-facturas');
        return view('tipos_retencion.edit', compact('tipos_retencion'));
    }

    public function update(Request $request, TipoRetencion $tipos_retencion)
    {
        $this->authorizePermission('crear-facturas');
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:tipos_retencion,nombre,' . $tipos_retencion->id,
            'slug' => 'nullable|string|max:255|unique:tipos_retencion,slug,' . $tipos_retencion->id,
            'porcentaje' => 'required|numeric|min:0|max:100',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $validated['activo'] = $request->boolean('activo');

        $tipos_retencion->update($validated);

        return redirect()->route('tipos-retencion.index')->with('success', 'Tipo de retención actualizado exitosamente.');
    }

    public function destroy(TipoRetencion $tipos_retencion)
    {
        $this->authorizePermission('crear-facturas');
        if ($tipos_retencion->retenciones()->count() > 0) {
            return redirect()->route('tipos-retencion.index')
                ->with('error', 'No se puede eliminar un tipo de retención con facturas asociadas.');
        }

        $tipos_retencion->delete();
        return redirect()->route('tipos-retencion.index')->with('success', 'Tipo de retención eliminado exitosamente.');
    }
}

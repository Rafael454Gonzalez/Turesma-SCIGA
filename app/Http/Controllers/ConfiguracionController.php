<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function index()
    {
        $logoExiste = Storage::disk('public')->exists('logo.png');
        $logoUrl = $logoExiste ? asset('storage/logo.png') : null;

        return view('configuracion.index', compact('logoExiste', 'logoUrl'));
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:png,jpg,jpeg,webp|max:2048',
        ]);

        Storage::disk('public')->delete('logo.png');

        $request->file('logo')->storeAs('', 'logo.png', 'public');

        return redirect()->route('configuracion')->with('success', 'Logo actualizado correctamente.');
    }

    public function deleteLogo()
    {
        Storage::disk('public')->delete('logo.png');

        return redirect()->route('configuracion')->with('success', 'Logo eliminado.');
    }
}

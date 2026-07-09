@extends('layouts.master')

@section('title', 'Configuración')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Configuración</h2>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">Personaliza tu sistema</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-sm font-bold text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
        <h3 class="text-sm font-black italic tracking-tighter text-[#0a0a0a] mb-4">Logo de la Empresa</h3>
        <p class="text-[11px] text-slate-500 mb-4">Este logo aparecerá en los reportes PDF generados por el sistema. Formatos: PNG, JPG, WebP. Máximo 2MB.</p>

        <div class="flex items-center gap-6 mb-6">
            @if ($logoExiste && $logoUrl)
                <div class="relative">
                    <img src="{{ $logoUrl }}" alt="Logo" class="h-24 w-auto rounded-xl border border-slate-200 p-2 bg-white" id="logo-preview">
                </div>
            @else
                <div class="h-24 w-48 rounded-xl border-2 border-dashed border-slate-200 flex items-center justify-center bg-slate-50" id="logo-preview-container">
                    <span class="text-[11px] font-bold text-slate-400" id="logo-placeholder">Sin logo</span>
                </div>
            @endif
        </div>

        <form action="{{ route('configuracion.logo.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="logo-form">
            @csrf

            <div onclick="document.getElementById('logo-input').click()"
                 class="border-2 border-dashed border-slate-300 rounded-xl p-8 text-center cursor-pointer hover:border-[#E31E24] hover:bg-red-50 transition-all" id="dropzone">
                <div class="text-3xl mb-2">🖼️</div>
                <p class="text-sm font-bold text-slate-500">Haz clic aquí para seleccionar una imagen</p>
                <p class="text-[10px] text-slate-400 mt-1">o arrastra y suelta el archivo aquí</p>
                <p class="text-[10px] font-bold text-slate-400 mt-3" id="file-name"></p>
                <input type="file" name="logo" id="logo-input" accept="image/png,image/jpg,image/jpeg,image/webp" class="hidden">
            </div>

            @error('logo')
                <p class="text-[10px] font-bold text-red-500 mt-1">{{ $message }}</p>
            @enderror

            <div class="flex items-center gap-3">
                <button type="submit" id="submit-logo" disabled
                    class="px-5 py-2.5 bg-slate-300 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all cursor-not-allowed">
                    Subir Logo
                </button>
                @if ($logoExiste)
                    <a href="{{ route('configuracion.logo.delete') }}" class="px-5 py-2.5 border border-red-200 text-red-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all"
                       onclick="return confirm('¿Eliminar logo actual?')">
                        Eliminar
                    </a>
                @endif
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
        document.getElementById('logo-input').addEventListener('change', function(e) {
            var file = e.target.files[0];
            var nameEl = document.getElementById('file-name');
            var btn = document.getElementById('submit-logo');

            if (file) {
                nameEl.textContent = '📎 ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)';
                btn.disabled = false;
                btn.className = 'px-5 py-2.5 bg-[#E31E24] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-red-700 transition-all cursor-pointer';
            } else {
                nameEl.textContent = '';
                btn.disabled = true;
                btn.className = 'px-5 py-2.5 bg-slate-300 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all cursor-not-allowed';
            }
        });

        document.getElementById('logo-form').addEventListener('submit', function(e) {
            var btn = document.getElementById('submit-logo');
            btn.disabled = true;
            btn.textContent = 'Subiendo...';
        });
    </script>
    @endpush
</div>
@endsection

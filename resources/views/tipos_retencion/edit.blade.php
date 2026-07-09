@extends('layouts.master')

@section('title', 'Editar Tipo de Retención')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Editar Tipo de Retención: {{ $tipos_retencion->nombre }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 border-b-8 border-[#E31E24]">
    <form method="POST" action="{{ route('tipos-retencion.update', $tipos_retencion) }}">
        @csrf
        @method('PUT')
        @include('tipos_retencion._form', ['tipos_retencion' => $tipos_retencion])

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Actualizar</button>
            <a href="{{ route('tipos-retencion.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
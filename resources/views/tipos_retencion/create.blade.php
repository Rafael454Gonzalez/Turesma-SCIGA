@extends('layouts.master')

@section('title', 'Nuevo Tipo de Retención')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Nuevo Tipo de Retención</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 border-b-8 border-[#E31E24]">
    <form method="POST" action="{{ route('tipos-retencion.store') }}">
        @csrf
        @include('tipos_retencion._form', ['tipos_retencion' => null])

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Guardar</button>
            <a href="{{ route('tipos-retencion.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection
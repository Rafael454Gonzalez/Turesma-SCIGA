@extends('layouts.master')

@section('title', 'Editar Factura')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Editar Factura #{{ $factura->numero_factura }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 border-b-8 border-[#E31E24]">
    <form method="POST" action="{{ route('facturas.update', $factura) }}">
        @csrf
        @method('PUT')
        @include('facturas._form', ['factura' => $factura])

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Actualizar</button>
            <a href="{{ route('facturas.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection

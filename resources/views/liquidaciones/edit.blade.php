@extends('layouts.master')

@section('title', 'Editar Liquidación')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Liquidación #{{ $liquidacion->id }}</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 border-b-8 border-[#E31E24]">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Socio</label>
            <p class="text-sm font-bold text-slate-700">{{ $liquidacion->socio->nombres }}</p>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Período</label>
            @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
            <p class="text-sm font-bold text-slate-700">{{ $mesNombres[$liquidacion->periodo_mes] ?? $liquidacion->periodo_mes }} {{ $liquidacion->periodo_anio }}</p>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Total Facturado</label>
            <p class="text-sm font-bold text-slate-700">$ {{ number_format($liquidacion->total_facturado, 2) }}</p>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Total Neto</label>
            <p class="text-sm font-bold text-[#E31E24]">$ {{ number_format($liquidacion->total_neto, 2) }}</p>
        </div>
    </div>

    {{-- Facturas incluidas --}}
    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Facturas Incluidas</h4>
    <div class="overflow-x-auto mb-6">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-100">
                    <th class="py-2 px-3"># Factura</th>
                    <th class="py-2 px-3">Importe Aplicado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($liquidacion->detalles as $det)
                <tr class="border-b border-slate-100">
                    <td class="py-2 px-3 text-sm font-bold text-slate-700">{{ $det->factura->numero_factura }}</td>
                    <td class="py-2 px-3 text-sm font-bold text-slate-700">$ {{ number_format($det->importe_aplicado, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Cambiar estado --}}
    <form method="POST" action="{{ route('liquidaciones.update', $liquidacion) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Estado</label>
                <select name="estado"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                    <option value="borrador" {{ $liquidacion->estado == 'borrador' ? 'selected' : '' }}>Borrador</option>
                    <option value="emitido" {{ $liquidacion->estado == 'emitido' ? 'selected' : '' }}>Emitido</option>
                    <option value="aprobado" {{ $liquidacion->estado == 'aprobado' ? 'selected' : '' }}>Aprobado</option>
                    <option value="cerrado" {{ $liquidacion->estado == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                </select>
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-slate-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Actualizar Estado</button>
            <a href="{{ route('liquidaciones.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Volver</a>
        </div>
    </form>
</div>
@endsection
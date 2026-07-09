@extends('layouts.master')

@section('title', 'Generar Liquidación')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Generar Liquidación</h2>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 border-b-8 border-[#E31E24]">
    <form method="GET" action="{{ route('liquidaciones.create') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Socio <span class="text-[#E31E24]">*</span></label>
            <select name="socio_id" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Seleccione un socio</option>
                @foreach ($socios as $s)
                    <option value="{{ $s->id }}" {{ request('socio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Mes <span class="text-[#E31E24]">*</span></label>
            <select name="mes" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Seleccione un mes</option>
                @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
                @foreach (range(1, 12) as $m)
                    <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Año <span class="text-[#E31E24]">*</span></label>
            <select name="anio" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Seleccione un año</option>
                @foreach ($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            @if (request()->hasAny(['socio_id', 'mes', 'anio']))
                <a href="{{ route('liquidaciones.create') }}"
                    class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Limpiar</a>
            @endif
        </div>
    </form>

    @if (request()->has(['socio_id', 'mes', 'anio']) && $facturasDisponibles->isEmpty() && $yaLiquidadas->isEmpty())
        <div class="text-sm font-bold text-slate-500 text-center py-8">
            No hay facturas para el socio y período seleccionado.
        </div>
    @endif

    @if ($facturasDisponibles->isNotEmpty())
        <form method="POST" action="{{ route('liquidaciones.store') }}">
            @csrf
            <input type="hidden" name="socio_id" value="{{ request('socio_id') }}">
            <input type="hidden" name="periodo_mes" value="{{ request('mes') }}">
            <input type="hidden" name="periodo_anio" value="{{ request('anio') }}">

            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Facturas Disponibles</h4>
            <div class="overflow-x-auto mb-6">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-100">
                            <th class="py-2 px-3 w-10">
                                <input type="checkbox" onclick="toggleAll(this)"
                                    class="rounded border-slate-300 text-[#E31E24] focus:ring-[#E31E24]">
                            </th>
                            <th class="py-2 px-3"># Factura</th>
                            <th class="py-2 px-3">Fecha</th>
                            <th class="py-2 px-3">Cliente</th>
                            <th class="py-2 px-3 text-right">Valor Bruto</th>
                            <th class="py-2 px-3 text-right">V. Recibido</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($facturasDisponibles as $f)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-2 px-3">
                                <input type="checkbox" name="factura_ids[]" value="{{ $f->id }}"
                                    class="factura-check rounded border-slate-300 text-[#E31E24] focus:ring-[#E31E24]">
                            </td>
                            <td class="py-2 px-3 text-sm font-bold text-slate-700">{{ $f->numero_factura }}</td>
                            <td class="py-2 px-3 text-sm font-bold text-slate-600">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                            <td class="py-2 px-3 text-sm font-bold text-slate-700">{{ $f->cliente->razon_social }}</td>
                            <td class="py-2 px-3 text-right text-sm font-bold text-slate-700">$ {{ number_format($f->valor_bruto, 2) }}</td>
                            <td class="py-2 px-3 text-right text-sm font-bold text-slate-700">$ {{ number_format($f->valor_recibido, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex items-center gap-3 pt-4 border-t border-slate-200">
                <button type="submit"
                    class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Generar Liquidación</button>
                <a href="{{ route('liquidaciones.index') }}"
                    class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
            </div>
        </form>
    @endif

    @if ($yaLiquidadas->isNotEmpty())
        <div class="mt-6 pt-4 border-t border-slate-200">
            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">Facturas ya liquidadas</h4>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-100">
                            <th class="py-2 px-3"># Factura</th>
                            <th class="py-2 px-3">Fecha</th>
                            <th class="py-2 px-3">Cliente</th>
                            <th class="py-2 px-3 text-right">Valor Bruto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($yaLiquidadas as $f)
                        <tr class="border-b border-slate-100 text-slate-400">
                            <td class="py-2 px-3 text-sm font-bold">{{ $f->numero_factura }}</td>
                            <td class="py-2 px-3 text-sm font-bold">{{ $f->fecha_emision->format('d/m/Y') }}</td>
                            <td class="py-2 px-3 text-sm font-bold">{{ $f->cliente->razon_social }}</td>
                            <td class="py-2 px-3 text-right text-sm font-bold">$ {{ number_format($f->valor_bruto, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function toggleAll(src) {
        document.querySelectorAll('.factura-check').forEach(cb => cb.checked = src.checked);
    }
</script>
@endpush
@endsection
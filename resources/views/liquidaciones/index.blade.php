@extends('layouts.master')

@section('title', 'Liquidaciones')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Liquidaciones</h2>
    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $liquidaciones->count() }} registros</span>
        <a href="{{ route('liquidaciones.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Generar Liquidación</a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

{{-- FILTROS --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400">Filtrar resultados</h4>
        @if (request()->hasAny(['socio_id', 'periodo_mes', 'periodo_anio']))
            <a href="{{ route('liquidaciones.index') }}" class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Limpiar filtros</a>
        @endif
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Socio</label>
            <select name="socio_id" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los socios</option>
                @foreach ($socios as $s)
                    <option value="{{ $s->id }}" {{ request('socio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Mes</label>
            <select name="periodo_mes" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los meses</option>
                @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
                @foreach ($mesesDisponibles as $m)
                    <option value="{{ $m }}" {{ request('periodo_mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] ?? $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Año</label>
            <select name="periodo_anio" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los años</option>
                @foreach ($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ request('periodo_anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Filtrar</button>
        </div>
    </form>
</div>

{{-- TABLA --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-100">
                    <th class="py-3 px-5">#</th>
                    <th class="py-3 px-5">Socio</th>
                    <th class="py-3 px-5">Período</th>
                    <th class="py-3 px-5 text-right">Total Facturado</th>
                    <th class="py-3 px-5 text-right">Retenciones</th>
                    <th class="py-3 px-5 text-right">Total Neto</th>
                    <th class="py-3 px-5">Facturas</th>
                    <th class="py-3 px-5 text-center">Estado</th>
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($liquidaciones as $l)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-5 text-slate-500 text-xs font-bold">{{ $l->id }}</td>
                    <td class="py-3 px-5 text-sm font-bold text-slate-700">{{ $l->socio->nombres }}</td>
                    <td class="py-3 px-5 text-sm font-bold text-slate-700">{{ $mesNombres[$l->periodo_mes] ?? $l->periodo_mes }} {{ $l->periodo_anio }}</td>
                    <td class="py-3 px-5 text-right text-sm font-bold text-slate-700">$ {{ number_format($l->total_facturado, 2) }}</td>
                    <td class="py-3 px-5 text-right text-sm font-bold text-slate-700">$ {{ number_format($l->total_retenciones, 2) }}</td>
                    <td class="py-3 px-5 text-right text-sm font-bold text-slate-700">$ {{ number_format($l->total_neto, 2) }}</td>
                    <td class="py-3 px-5">
                        @foreach ($l->detalles as $det)
                            <span class="text-xs font-bold text-slate-600 block">Factura #{{ $det->factura->numero_factura }}: $ {{ number_format($det->importe_aplicado, 2) }}</span>
                        @endforeach
                    </td>
                    <td class="py-3 px-5 text-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full
                            {{ $l->estado === 'calculado' ? 'bg-purple-50 text-purple-700 border border-purple-200' : '' }}
                            {{ $l->estado === 'aprobado' || $l->estado === 'cerrado' ? 'bg-green-50 text-green-700 border border-green-200' : '' }}
                            {{ $l->estado === 'emitido' ? 'bg-blue-50 text-blue-700 border border-blue-200' : '' }}
                            {{ $l->estado === 'borrador' ? 'bg-slate-50 text-slate-600 border border-slate-200' : '' }}">
                            {{ ucfirst($l->estado) }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        @if ($l->id)
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('liquidaciones.edit', $l) }}"
                                class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Editar</a>
                            <form method="POST" action="{{ route('liquidaciones.destroy', $l) }}" onsubmit="return confirm('¿Eliminar liquidación #{{ $l->id }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors cursor-pointer">Eliminar</button>
                            </form>
                        </div>
                        @else
                        <span class="text-xs font-bold text-slate-400">Automático</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="py-8 text-center text-sm font-bold text-slate-400">No hay liquidaciones registradas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
@extends('layouts.master')

@section('title', 'Facturas')

@section('content')
@php
    $nombresMeses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
@endphp
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Facturas</h2>
    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $facturas->total() }} registros</span>
        @permission('crear-facturas')
        <a href="{{ route('facturas.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nueva Factura</a>
        @endpermission
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
        @if (request()->hasAny(['socio_id', 'cliente_id', 'estado', 'anio', 'mes']))
            <a href="{{ route('facturas.index') }}" class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Limpiar filtros</a>
        @endif
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4">
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Socio</label>
            <select name="socio_id" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los socios</option>
                @foreach ($socios as $s)
                    <option value="{{ $s->id }}" {{ request('socio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Cliente</label>
            <select name="cliente_id" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los clientes</option>
                @foreach ($clientes as $c)
                    <option value="{{ $c->id }}" {{ request('cliente_id') == $c->id ? 'selected' : '' }}>{{ $c->razon_social }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Estado</label>
            <select name="estado" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los estados</option>
                @foreach ($estados as $e)
                    <option value="{{ $e }}" {{ request('estado') == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Año</label>
            <select name="anio" id="filtro-anio" onchange="cargarMeses()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los años</option>
                @foreach ($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ $anioEfectivo == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Mes</label>
            <select name="mes" id="filtro-mes" onchange="this.form.submit()"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                <option value="">Todos los meses</option>
                @foreach ($mesesDisponibles as $m)
                    <option value="{{ $m }}" {{ $mesEfectivo == $m ? 'selected' : '' }}>{{ $nombresMeses[$m] }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex items-end">
            <button type="submit"
                class="px-5 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Filtrar</button>
        </div>
    </form>
</div>

{{-- TABLA --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-200">
                    <th class="py-3.5 px-4"># Factura</th>
                    <th class="py-3.5 px-4">Fecha</th>
                    <th class="py-3.5 px-4">Socio</th>
                    <th class="py-3.5 px-4">Cliente</th>
                    <th class="py-3.5 px-4 text-right">Valor Bruto</th>
                    <th class="py-3.5 px-4 text-right">Ret. Pers.</th>
                    <th class="py-3.5 px-4 text-right">V. Recibido</th>
                    <th class="py-3.5 px-4 text-right">Ret. Turesma</th>
                    <th class="py-3.5 px-4 text-right">Valor Neto</th>
                    <th class="py-3.5 px-4 text-center">Estado</th>
                    <th class="py-3.5 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($facturas as $f)
                @php
                    $retenciones = $f->retenciones;
                    $ret1 = $f->valor_bruto - $f->valor_recibido;
                    $ret3 = $retenciones->where('tipoRetencion.slug', 'retencion-turismo-3')->sum('valor_retencion');
                    $valorNeto = $f->valor_recibido - $ret3;
                @endphp
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-4 font-bold text-slate-800">{{ $f->numero_factura }}</td>
                    <td class="py-3.5 px-4 text-slate-500">{{ \Carbon\Carbon::parse($f->fecha_emision)->format('d/m/Y') }}</td>
                    <td class="py-3.5 px-4 text-slate-700">{{ $f->socio->nombres }}</td>
                    <td class="py-3.5 px-4 text-slate-700">{{ $f->cliente->razon_social }}</td>
                    <td class="py-3.5 px-4 text-right text-slate-700">$ {{ number_format($f->valor_bruto, 2) }}</td>
                    <td class="py-3.5 px-4 text-right text-[#E31E24]">$ {{ number_format($ret1, 2) }}</td>
                    <td class="py-3.5 px-4 text-right font-bold text-slate-800">$ {{ number_format($f->valor_recibido, 2) }}</td>
                    <td class="py-3.5 px-4 text-right text-[#E31E24]">$ {{ number_format($ret3, 2) }}</td>
                    <td class="py-3.5 px-4 text-right font-bold text-slate-800">$ {{ number_format($valorNeto, 2) }}</td>
                    <td class="py-3.5 px-4 text-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full
                            {{ strtolower($f->estado_factura) === 'pagado' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                            {{ strtolower($f->estado_factura) === 'pendiente' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                            {{ strtolower($f->estado_factura) === 'anulado' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}">
                            {{ ucfirst($f->estado_factura) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @permission('editar-facturas')
                            <a href="{{ route('facturas.edit', $f) }}"
                                class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Editar</a>
                            @endpermission
                            @permission('anular-facturas')
                            <form method="POST" action="{{ route('facturas.destroy', $f) }}" onsubmit="return confirm('¿Eliminar factura #{{ $f->numero_factura }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors cursor-pointer">Eliminar</button>
                            </form>
                            @endpermission
                        </div>
                    </td>
                </tr>
                @if ($f->distribuciones->isNotEmpty())
                <tr class="bg-slate-50 border-b border-slate-200">
                    <td colspan="11" class="py-2 px-8 text-[10px] font-bold text-slate-500">
                        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Retenciones Personales:</span>
                        @foreach ($f->distribuciones as $d)
                            <span class="ml-3">
                                {{ $d->porcentaje ? $d->porcentaje . '%' : '' }}
                                {{ $d->observacion ? '(' . $d->observacion . ')' : '' }}
                                $ {{ number_format($d->valor, 2) }}
                            </span>
                        @endforeach
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="11" class="py-8 text-center text-slate-400">No hay facturas para el filtro seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-200 flex items-center justify-between">
        <form method="GET" action="{{ route('facturas.index') }}" class="flex items-center gap-2">
            @foreach (request()->except('per_page', 'page') as $name => $value)
                @if ($value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endforeach
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mostrar</label>
            <select name="per_page" onchange="this.form.submit()"
                class="text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#E31E24] cursor-pointer">
                <option value="5" {{ (request('per_page', 10) == 5) ? 'selected' : '' }}>5</option>
                <option value="10" {{ (request('per_page', 10) == 10) ? 'selected' : '' }}>10</option>
                <option value="20" {{ (request('per_page', 10) == 20) ? 'selected' : '' }}>20</option>
            </select>
        </form>
        {{ $facturas->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cargarMeses() {
        const anio = document.getElementById('filtro-anio').value;
        const mesSelect = document.getElementById('filtro-mes');

        if (!anio) {
            mesSelect.innerHTML = '<option value="">Todos los meses</option>';
            return;
        }

        fetch('{{ route("facturas.meses", ["anio" => "_ANIO_"]) }}'.replace('_ANIO_', anio))
            .then(response => response.json())
            .then(meses => {
                mesSelect.innerHTML = '<option value="">Todos los meses</option>';
                const nombres = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
                meses.forEach(m => {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = nombres[m];
                    mesSelect.appendChild(opt);
                });
            });
    }
</script>
@endpush

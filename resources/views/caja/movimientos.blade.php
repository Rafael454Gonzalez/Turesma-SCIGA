@extends('layouts.master')

@section('title', 'Movimientos de Caja')

@section('content')
@php
    $nombresMeses = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
@endphp
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Movimientos de Caja</h2>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ $movimientos->count() }} registros</span>
        <a href="{{ route('caja.movimientos.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Movimiento</a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-100 border border-green-300 text-green-700 text-sm rounded-lg px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

{{-- FILTROS --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Filtrar resultados</h4>
        @if (request()->hasAny(['tipo', 'categoria_id', 'anio', 'mes']))
            <a href="{{ route('caja.movimientos.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">Limpiar filtros</a>
        @endif
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Tipo</label>
            <select name="tipo" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los tipos</option>
                @foreach ($tiposDisponibles as $t)
                    <option value="{{ $t }}" {{ request('tipo') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Categoría</label>
            <select name="categoria_id" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ request('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Año</label>
            <select name="anio" id="filtro-anio" onchange="cargarMeses()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los años</option>
                @foreach ($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ $anioEfectivo == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Mes</label>
            <select name="mes" id="filtro-mes" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
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
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 uppercase text-xs bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-5">Fecha</th>
                    <th class="py-3 px-5">Tipo</th>
                    <th class="py-3 px-5">Categoría</th>
                    <th class="py-3 px-5">Descripción</th>
                    <th class="py-3 px-5 text-right">Valor</th>
                    <th class="py-3 px-5 text-center">Estado</th>
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($movimientos as $m)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-5">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</td>
                    <td class="py-3 px-5">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $m->tipo === 'ingreso' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $m->tipo === 'egreso' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $m->tipo === 'ajuste' ? 'bg-yellow-100 text-yellow-700' : '' }}">
                            {{ ucfirst($m->tipo) }}
                        </span>
                    </td>
                    <td class="py-3 px-5">{{ $m->categoria->nombre }}</td>
                    <td class="py-3 px-5 text-gray-600">{{ $m->descripcion }}</td>
                    <td class="py-3 px-5 text-right font-medium {{ $m->tipo === 'egreso' ? 'text-red-600' : 'text-green-600' }}">
                        $ {{ number_format($m->valor, 2) }}
                    </td>
                    <td class="py-3 px-5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $m->estado === 'activo' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($m->estado) }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('caja.movimientos.edit', $m) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</a>
                            <form method="POST" action="{{ route('caja.movimientos.destroy', $m) }}" onsubmit="return confirm('¿Eliminar este movimiento?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium cursor-pointer">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-8 text-center text-gray-400">No hay movimientos para el filtro seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
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

        fetch('{{ route("caja.movimientos.meses", ["anio" => "_ANIO_"]) }}'.replace('_ANIO_', anio))
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

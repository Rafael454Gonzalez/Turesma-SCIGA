@extends('layouts.master')

@section('title', 'Aportes de Socios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Aportes de Socios</h2>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ $aportes->total() }} registros</span>
        @permission('crear-movimientos')
        <a href="{{ route('caja.aportes.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Aporte</a>
        @endpermission
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
        @if (request()->hasAny(['socio_id', 'periodo_mes', 'periodo_anio', 'estado_pago']))
            <a href="{{ route('caja.aportes.index') }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium hover:underline">Limpiar filtros</a>
        @endif
    </div>
    <form method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Socio</label>
            <select name="socio_id" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los socios</option>
                @foreach ($socios as $s)
                    <option value="{{ $s->id }}" {{ request('socio_id') == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Mes</label>
            <select name="periodo_mes" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los meses</option>
                @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
                @foreach ($mesesDisponibles as $m)
                    <option value="{{ $m }}" {{ request('periodo_mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] ?? $m }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Año</label>
            <select name="periodo_anio" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los años</option>
                @foreach ($aniosDisponibles as $a)
                    <option value="{{ $a }}" {{ request('periodo_anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Estado</label>
            <select name="estado_pago" onchange="this.form.submit()"
                class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
                <option value="">Todos los estados</option>
                @foreach ($estadosDisponibles as $e)
                    <option value="{{ $e }}" {{ request('estado_pago') == $e ? 'selected' : '' }}>{{ ucfirst($e) }}</option>
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
                    <th class="py-3 px-5">Socio</th>
                    <th class="py-3 px-5">Período</th>
                    <th class="py-3 px-5 text-right">Valor Cuota</th>
                    <th class="py-3 px-5">Fecha Pago</th>
                    <th class="py-3 px-5 text-center">Estado</th>
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aportes as $a)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-5 font-medium">{{ $a->socio->nombres }}</td>
                    <td class="py-3 px-5">{{ $mesNombres[$a->periodo_mes] ?? $a->periodo_mes }} {{ $a->periodo_anio }}</td>
                    <td class="py-3 px-5 text-right">$ {{ number_format($a->valor_cuota, 2) }}</td>
                    <td class="py-3 px-5 text-gray-600">{{ $a->fecha_pago ? \Carbon\Carbon::parse($a->fecha_pago)->format('d/m/Y') : '—' }}</td>
                    <td class="py-3 px-5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $a->estado_pago === 'pagado' ? 'bg-green-100 text-green-700' : '' }}
                            {{ $a->estado_pago === 'pendiente' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $a->estado_pago === 'cancelado' ? 'bg-red-100 text-red-700' : '' }}
                            {{ $a->estado_pago === 'exento' ? 'bg-gray-100 text-gray-600' : '' }}">
                            {{ ucfirst($a->estado_pago) }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @permission('crear-movimientos')
                            <a href="{{ route('caja.aportes.edit', $a) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</a>
                            <form method="POST" action="{{ route('caja.aportes.destroy', $a) }}" onsubmit="return confirm('¿Eliminar este aporte?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium cursor-pointer">Eliminar</button>
                            </form>
                            @endpermission
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-8 text-center text-gray-400">No hay aportes para el filtro seleccionado.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-200 flex items-center justify-between">
        <form method="GET" action="{{ route('caja.aportes.index') }}" class="flex items-center gap-2">
            @foreach (request()->except('per_page', 'page') as $name => $value)
                @if ($value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endif
            @endforeach
            <label class="text-xs font-medium text-gray-500">Mostrar</label>
            <select name="per_page" onchange="this.form.submit()"
                class="text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer">
                <option value="5" {{ (request('per_page', 10) == 5) ? 'selected' : '' }}>5</option>
                <option value="10" {{ (request('per_page', 10) == 10) ? 'selected' : '' }}>10</option>
                <option value="20" {{ (request('per_page', 10) == 20) ? 'selected' : '' }}>20</option>
            </select>
        </form>
        {{ $aportes->links() }}
    </div>
</div>
@endsection

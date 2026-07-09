@extends('layouts.master')

@section('title', 'Editar Aporte')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Editar Aporte de Socio</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('caja.aportes.update', $aporte) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Socio <span class="text-red-500">*</span></label>
                <select name="socio_id"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                    <option value="">Seleccione</option>
                    @foreach ($socios as $s)
                        <option value="{{ $s->id }}" {{ old('socio_id', $aporte->socio_id) == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
                    @endforeach
                </select>
                @error('socio_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Mes <span class="text-red-500">*</span></label>
                <select name="periodo_mes"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                    <option value="">Seleccione</option>
                    @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
                    @foreach (range(1, 12) as $m)
                        <option value="{{ $m }}" {{ old('periodo_mes', $aporte->periodo_mes) == $m ? 'selected' : '' }}>{{ $mesNombres[$m] }}</option>
                    @endforeach
                </select>
                @error('periodo_mes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Año <span class="text-red-500">*</span></label>
                <input type="number" name="periodo_anio" value="{{ old('periodo_anio', $aporte->periodo_anio) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                @error('periodo_anio') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor Cuota <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">$</span>
                    <input type="number" step="0.01" min="0" name="valor_cuota" value="{{ old('valor_cuota', $aporte->valor_cuota) }}"
                        class="w-full text-sm border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                </div>
                @error('valor_cuota') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estado <span class="text-red-500">*</span></label>
                <select name="estado_pago"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                    <option value="pendiente" {{ old('estado_pago', $aporte->estado_pago) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="pagado" {{ old('estado_pago', $aporte->estado_pago) == 'pagado' ? 'selected' : '' }}>Pagado</option>
                    <option value="cancelado" {{ old('estado_pago', $aporte->estado_pago) == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    <option value="exento" {{ old('estado_pago', $aporte->estado_pago) == 'exento' ? 'selected' : '' }}>Exento</option>
                </select>
                @error('estado_pago') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha Pago</label>
                <input type="date" name="fecha_pago" value="{{ old('fecha_pago', $aporte->fecha_pago?->format('Y-m-d')) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('fecha_pago') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Observación</label>
                <input type="text" name="observacion" value="{{ old('observacion', $aporte->observacion) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                @error('observacion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Actualizar</button>
            <a href="{{ route('caja.aportes.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection

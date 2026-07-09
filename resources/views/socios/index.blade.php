@extends('layouts.master')

@section('title', 'Socios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Socios</h2>
    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $socios->count() }} registros</span>
        <a href="{{ route('socios.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Socio</a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-200">
                    <th class="py-3.5 px-5">Nombre</th>
                    <th class="py-3.5 px-5">Identificación</th>
                    <th class="py-3.5 px-5">Teléfono</th>
                    <th class="py-3.5 px-5">Tipo</th>
                    <th class="py-3.5 px-5 text-center">Facturas</th>
                    <th class="py-3.5 px-5 text-center">Aportes</th>
                    <th class="py-3.5 px-5 text-center">Estado</th>
                    <th class="py-3.5 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($socios as $s)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-5 font-bold text-slate-800">{{ $s->nombres }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $s->identificacion }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $s->telefono ?? '—' }}</td>
                    <td class="py-3.5 px-5">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full {{ $s->tipo_socio === 'socio' ? 'bg-[#E31E24]/10 text-[#E31E24] border border-[#E31E24]/20' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            {{ ucfirst($s->tipo_socio) }}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center font-bold text-slate-700">{{ $s->facturas_count }}</td>
                    <td class="py-3.5 px-5 text-center font-bold text-slate-700">{{ $s->aportes_count }}</td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full {{ $s->activo ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                            {{ $s->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('socios.edit', $s) }}"
                                class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Editar</a>
                            <form method="POST" action="{{ route('socios.destroy', $s) }}" onsubmit="return confirm('¿Eliminar socio {{ $s->nombres }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors cursor-pointer">Eliminar</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

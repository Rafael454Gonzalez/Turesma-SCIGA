@extends('layouts.master')

@section('title', 'Tipos de Retención')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Tipos de Retención</h2>
    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $tipos->count() }} registros</span>
        <a href="{{ route('tipos-retencion.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Tipo</a>
    </div>
</div>

@if (session('success'))
    <div class="bg-green-50 border border-green-100 text-green-700 text-sm font-bold rounded-xl px-4 py-3 mb-6">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="bg-red-50 border border-red-100 text-red-700 text-sm font-bold rounded-xl px-4 py-3 mb-6">
        {{ session('error') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 text-[10px] font-black uppercase tracking-widest bg-slate-50 border-b border-slate-100">
                    <th class="py-3 px-5">Nombre</th>
                    <th class="py-3 px-5">Slug</th>
                    <th class="py-3 px-5 text-center">Porcentaje</th>
                    <th class="py-3 px-5">Descripción</th>
                    <th class="py-3 px-5 text-center">Estado</th>
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tipos as $t)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="py-3 px-5 text-sm font-bold text-slate-700">{{ $t->nombre }}</td>
                    <td class="py-3 px-5 text-sm font-bold text-slate-600">{{ $t->slug ?? '—' }}</td>
                    <td class="py-3 px-5 text-center text-sm font-bold text-slate-700">{{ $t->porcentaje }}%</td>
                    <td class="py-3 px-5 text-sm font-bold text-slate-600 max-w-xs truncate">{{ $t->descripcion ?? '—' }}</td>
                    <td class="py-3 px-5 text-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full {{ $t->activo ? 'bg-green-50 text-green-700 border border-green-200' : 'bg-red-50 text-red-700 border border-red-200' }}">
                            {{ $t->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('tipos-retencion.edit', $t) }}"
                                class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Editar</a>
                            <form method="POST" action="{{ route('tipos-retencion.destroy', $t) }}" onsubmit="return confirm('¿Eliminar tipo {{ $t->nombre }}?')">
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
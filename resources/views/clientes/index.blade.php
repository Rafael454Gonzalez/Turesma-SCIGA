@extends('layouts.master')

@section('title', 'Clientes')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Clientes</h2>
    <div class="flex items-center gap-3">
        <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">{{ $clientes->total() }} registros</span>
        @permission('crear-clientes')
        <a href="{{ route('clientes.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Cliente</a>
        @endpermission
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
                    <th class="py-3.5 px-5">Razón Social</th>
                    <th class="py-3.5 px-5">RUC</th>
                    <th class="py-3.5 px-5">Contacto</th>
                    <th class="py-3.5 px-5">Teléfono</th>
                    <th class="py-3.5 px-5">Email</th>
                    <th class="py-3.5 px-5 text-center">Facturas</th>
                    <th class="py-3.5 px-5 text-center">Estado</th>
                    <th class="py-3.5 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($clientes as $c)
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                    <td class="py-3.5 px-5 font-bold text-slate-800">{{ $c->razon_social }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $c->ruc }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $c->contacto ?? '—' }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $c->telefono ?? '—' }}</td>
                    <td class="py-3.5 px-5 text-slate-500">{{ $c->email ?? '—' }}</td>
                    <td class="py-3.5 px-5 text-center font-bold text-slate-700">{{ $c->facturas_count }}</td>
                    <td class="py-3.5 px-5 text-center">
                        <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full {{ $c->activo ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                            {{ $c->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="py-3.5 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            @permission('editar-clientes')
                            <a href="{{ route('clientes.edit', $c) }}"
                                class="text-[10px] font-black uppercase tracking-widest text-[#E31E24] hover:text-black transition-colors">Editar</a>
                            <form method="POST" action="{{ route('clientes.destroy', $c) }}" onsubmit="return confirm('¿Eliminar cliente {{ $c->razon_social }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors cursor-pointer">Eliminar</button>
                            </form>
                            @endpermission
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-slate-200 flex items-center justify-between">
        <form method="GET" action="{{ route('clientes.index') }}" class="flex items-center gap-2">
            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400">Mostrar</label>
            <select name="per_page" onchange="this.form.submit()"
                class="text-xs font-bold text-slate-700 bg-white border border-slate-200 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#E31E24] cursor-pointer">
                <option value="5" {{ (request('per_page', 10) == 5) ? 'selected' : '' }}>5</option>
                <option value="10" {{ (request('per_page', 10) == 10) ? 'selected' : '' }}>10</option>
                <option value="20" {{ (request('per_page', 10) == 20) ? 'selected' : '' }}>20</option>
            </select>
        </form>
        {{ $clientes->links() }}
    </div>
</div>
@endsection

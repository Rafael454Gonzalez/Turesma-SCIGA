@extends('layouts.master')

@section('title', 'Usuarios')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Usuarios del Sistema</h2>
    <div class="flex items-center gap-3">
        <span class="text-sm text-gray-500">{{ $usuarios->count() }} registros</span>
        <a href="{{ route('usuarios.create') }}"
            class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">+ Nuevo Usuario</a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-gray-500 uppercase text-xs bg-gray-50 border-b border-gray-200">
                    <th class="py-3 px-5">Nombre</th>
                    <th class="py-3 px-5">Email</th>
                    <th class="py-3 px-5">Rol Principal</th>
                    <th class="py-3 px-5">Socio Asociado</th>
                    <th class="py-3 px-5 text-center">Activo</th>
                    <th class="py-3 px-5 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $u)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-3 px-5 font-medium">{{ $u->name }}</td>
                    <td class="py-3 px-5 text-gray-600">{{ $u->email }}</td>
                    <td class="py-3 px-5">
                        <span class="px-2 py-0.5 rounded-full text-xs bg-blue-100 text-blue-700">
                            {{ $u->rolPrincipal->nombre ?? '—' }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-gray-600">{{ $u->socio->nombres ?? '—' }}</td>
                    <td class="py-3 px-5 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs {{ $u->activo ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $u->activo ? 'Sí' : 'No' }}
                        </span>
                    </td>
                    <td class="py-3 px-5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('usuarios.edit', $u) }}"
                                class="text-blue-600 hover:text-blue-800 text-xs font-medium">Editar</a>
                            <form method="POST" action="{{ route('usuarios.destroy', $u) }}" onsubmit="return confirm('¿Eliminar usuario {{ $u->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-red-600 hover:text-red-800 text-xs font-medium cursor-pointer">Eliminar</button>
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

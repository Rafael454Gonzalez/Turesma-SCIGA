@extends('layouts.master')

@section('title', 'Nuevo Usuario')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Nuevo Usuario</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('usuarios.store') }}">
        @csrf
        @include('usuarios._form', ['usuario' => null])

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Guardar</button>
            <a href="{{ route('usuarios.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>
@endsection

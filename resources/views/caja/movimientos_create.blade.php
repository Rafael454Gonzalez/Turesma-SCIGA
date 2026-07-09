@extends('layouts.master')

@section('title', 'Nuevo Movimiento')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Nuevo Movimiento de Caja</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form method="POST" action="{{ route('caja.movimientos.store') }}">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fecha <span class="text-red-500">*</span></label>
                <input type="date" name="fecha" value="{{ old('fecha', date('Y-m-d')) }}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                @error('fecha') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo <span class="text-red-500">*</span></label>
                <select name="tipo"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                    <option value="ingreso" {{ old('tipo') == 'ingreso' ? 'selected' : '' }}>Ingreso</option>
                    <option value="egreso" {{ old('tipo') == 'egreso' ? 'selected' : '' }}>Egreso</option>
                    <option value="ajuste" {{ old('tipo') == 'ajuste' ? 'selected' : '' }}>Ajuste</option>
                </select>
                @error('tipo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoría <span class="text-red-500">*</span></label>
                <select name="categoria_id" id="catSelect"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white" required>
                    <option value="">Seleccione</option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                    <option value="__new__">+ Nuevo</option>
                </select>
                @error('categoria_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Valor <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500 text-sm">$</span>
                    <input type="number" step="0.01" min="0" name="valor" value="{{ old('valor') }}"
                        class="w-full text-sm border border-gray-300 rounded-lg pl-8 pr-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
                </div>
                @error('valor') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                <textarea name="descripcion" rows="2"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>{{ old('descripcion') }}</textarea>
                @error('descripcion') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-3 mt-6 pt-4 border-t border-gray-200">
            <button type="submit"
                class="px-6 py-3 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Guardar</button>
            <a href="{{ route('caja.movimientos.index') }}"
                class="px-6 py-3 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Cancelar</a>
        </div>
    </form>
</div>

<div id="modalCategoria" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center">
    <div class="bg-white/90 backdrop-blur-sm rounded-xl shadow-2xl border border-white/50 w-full max-w-md mx-4 p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-800">Nueva Categoría</h3>
            <button type="button" onclick="cerrarModalCategoria()" class="text-gray-400 hover:text-gray-600 text-xl leading-none cursor-pointer">&times;</button>
        </div>

        <div class="space-y-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Nombre</label>
                <input type="text" id="catNombre" autocomplete="off"
                    class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                <p id="catNombreError" class="text-xs text-red-600 mt-1 hidden"></p>
            </div>
            <div class="flex items-center gap-2">
                <button type="button" onclick="guardarCategoria(event)"
                    class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Guardar</button>
                <button type="button" onclick="cerrarModalCategoria()"
                    class="px-4 py-2 text-xs font-medium text-gray-600 hover:text-gray-800 transition cursor-pointer">Cancelar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('catSelect').addEventListener('change', function() {
        if (this.value === '__new__') {
            this.value = '';
            document.getElementById('modalCategoria').classList.remove('hidden');
            document.getElementById('catNombre').focus();
        }
    });

    function cerrarModalCategoria() {
        document.getElementById('modalCategoria').classList.add('hidden');
        document.getElementById('catNombre').value = '';
        document.getElementById('catNombreError').classList.add('hidden');
    }

    function guardarCategoria(event) {
        const nombre = document.getElementById('catNombre').value.trim();
        const errorEl = document.getElementById('catNombreError');

        if (!nombre) {
            errorEl.textContent = 'El nombre es obligatorio.';
            errorEl.classList.remove('hidden');
            return;
        }
        errorEl.classList.add('hidden');

        const btn = event.target;
        btn.disabled = true;
        btn.textContent = 'Guardando...';

        const tipoSelect = document.querySelector('select[name="tipo"]');
        const tipo = tipoSelect ? tipoSelect.value : 'ingreso';

        fetch('{{ route("caja.categorias.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ nombre, tipo }),
        })
        .then(async res => {
            if (!res.ok) {
                const text = await res.text();
                throw new Error(res.status + ' ' + res.statusText + (text.length < 200 ? ': ' + text : ''));
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                const select = document.getElementById('catSelect');
                const opt = document.createElement('option');
                opt.value = data.categoria.id;
                opt.textContent = data.categoria.nombre;
                opt.selected = true;
                select.appendChild(opt);
                cerrarModalCategoria();
            } else {
                errorEl.textContent = data.message || 'Error al guardar.';
                errorEl.classList.remove('hidden');
            }
        })
        .catch((err) => {
            errorEl.textContent = 'Error: ' + err.message;
            errorEl.classList.remove('hidden');
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Guardar';
        });
    }
</script>
@endpush
@endsection
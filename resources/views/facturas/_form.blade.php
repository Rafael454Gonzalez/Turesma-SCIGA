@php $factura ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Número Factura <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="numero_factura" value="{{ old('numero_factura', $factura?->numero_factura) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
        @error('numero_factura') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Fecha Emisión <span class="text-[#E31E24]">*</span></label>
        <input type="date" name="fecha_emision" value="{{ old('fecha_emision', $factura?->fecha_emision?->format('Y-m-d')) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
        @error('fecha_emision') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Socio <span class="text-[#E31E24]">*</span></label>
        <select name="socio_id"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
            <option value="">Seleccione un socio</option>
            @foreach ($socios as $s)
                <option value="{{ $s->id }}" {{ old('socio_id', $factura?->socio_id) == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
            @endforeach
        </select>
        @error('socio_id') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Cliente <span class="text-[#E31E24]">*</span></label>
        <select name="cliente_id"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
            <option value="">Seleccione un cliente</option>
            @foreach ($clientes as $c)
                <option value="{{ $c->id }}" {{ old('cliente_id', $factura?->cliente_id) == $c->id ? 'selected' : '' }}>{{ $c->razon_social }}</option>
            @endforeach
        </select>
        @error('cliente_id') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Valor Bruto <span class="text-[#E31E24]">*</span></label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">$</span>
            <input type="number" step="0.01" min="0" name="valor_bruto" id="valor_bruto"
                value="{{ old('valor_bruto', $factura?->valor_bruto) }}"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl pl-8 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
        </div>
        @error('valor_bruto') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Valor Recibido</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">$</span>
            <input type="number" step="0.01" min="0" name="valor_recibido" id="valor_recibido"
                value="{{ old('valor_recibido', $factura?->valor_recibido) }}"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl pl-8 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" readonly>
        </div>
        @error('valor_recibido') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Estado <span class="text-[#E31E24]">*</span></label>
        <select name="estado_factura"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
            <option value="pendiente" {{ old('estado_factura', $factura?->estado_factura) == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
            <option value="pagado" {{ old('estado_factura', $factura?->estado_factura) == 'pagado' ? 'selected' : '' }}>Pagado</option>
            <option value="anulado" {{ old('estado_factura', $factura?->estado_factura) == 'anulado' ? 'selected' : '' }}>Anulado</option>
        </select>
        @error('estado_factura') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="lg:col-span-3">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Observación</label>
        <textarea name="observacion" rows="2"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">{{ old('observacion', $factura?->observacion) }}</textarea>
        @error('observacion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>
</div>

{{-- RETENCION DE TURESMA --}}
<div class="mt-6 pt-4 border-t border-slate-200">
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-xs font-black italic tracking-tighter text-[#0a0a0a]">Retención de Turesma</h4>
    </div>
    <div id="retenciones-wrapper" class="space-y-2">
        @if ($factura)
            @foreach ($factura->retenciones as $i => $ret)
            <div class="retencion-row flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Tipo</label>
                    <select name="retenciones[{{ $i }}][tipo_retencion_id]"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                        @foreach ($tiposRetencion as $t)
                            <option value="{{ $t->id }}" {{ $ret->tipo_retencion_id == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                        @endforeach
                        <option value="__new__">➕ Nuevo...</option>
                    </select>
                </div>
                <div class="w-20">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">%</label>
                    <input type="number" step="0.01" name="retenciones[{{ $i }}][porcentaje]" value="{{ $ret->porcentaje }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Base</label>
                    <input type="number" step="0.01" name="retenciones[{{ $i }}][base_calculo]" value="{{ $ret->base_calculo }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" readonly>
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Valor</label>
                    <input type="number" step="0.01" name="retenciones[{{ $i }}][valor_retencion]" value="{{ $ret->valor_retencion }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
            </div>
            @endforeach
        @else
            <div class="retencion-row flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Tipo</label>
                    <select name="retenciones[0][tipo_retencion_id]"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                        @foreach ($tiposRetencion as $t)
                            <option value="{{ $t->id }}" {{ $loop->first ? 'selected' : '' }}>{{ $t->nombre }}</option>
                        @endforeach
                        <option value="__new__">➕ Nuevo...</option>
                    </select>
                </div>
                <div class="w-20">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">%</label>
                    <input type="number" step="0.01" name="retenciones[0][porcentaje]" id="porcentaje_ret" value="3"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Base</label>
                    <input type="number" step="0.01" name="retenciones[0][base_calculo]" id="base_calculo_ret"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" readonly>
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Valor</label>
                    <input type="number" step="0.01" name="retenciones[0][valor_retencion]" id="valor_retencion_3"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
            </div>
        @endif
    </div>
</div>

{{-- RETENCIONES PERSONALES --}}
<div class="mt-6 pt-4 border-t border-slate-200">
    <div class="flex items-center justify-between mb-3">
        <h4 class="text-xs font-black italic tracking-tighter text-[#0a0a0a]">Retenciones Personales</h4>
        <button type="button" onclick="addDistribucion()"
            class="px-3 py-1.5 bg-slate-100 text-slate-700 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-[#E31E24] hover:text-white transition-all cursor-pointer">+ Agregar</button>
    </div>
    <div id="distribuciones-wrapper" class="space-y-2">
        @if ($factura)
            @foreach ($factura->distribuciones as $i => $dist)
            @if ($dist->porcentaje)
            @php
                $distT = $tiposRetencion->firstWhere('porcentaje', $dist->porcentaje)?->id;
            @endphp
            <div class="distribucion-row flex items-end gap-2">
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Tipo</label>
                    <select name="distribuciones[{{ $i }}][tipo_retencion_id]"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                        <option value="">Seleccione</option>
                        @foreach ($tiposRetencion as $t)
                            <option value="{{ $t->id }}" {{ $distT == $t->id ? 'selected' : '' }}>{{ $t->nombre }}</option>
                        @endforeach
                        <option value="__new__">➕ Nuevo...</option>
                    </select>
                </div>
                <div class="w-20">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">%</label>
                    <input type="number" step="0.01" min="0" max="100" name="distribuciones[{{ $i }}][porcentaje]" value="{{ $dist->porcentaje }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Base</label>
                    <input type="number" step="0.01" min="0" name="distribuciones[{{ $i }}][base_calculo]" value="{{ $dist->base_calculo ?: $factura?->valor_bruto }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Observación</label>
                    <input type="text" name="distribuciones[{{ $i }}][observacion]" value="{{ $dist->observacion }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <div class="w-28">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Valor</label>
                    <input type="number" step="0.01" name="distribuciones[{{ $i }}][valor]" value="{{ $dist->valor }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-3 py-2 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                </div>
                <button type="button" onclick="removeDistribucion(this)"
                    class="px-2 py-1.5 text-slate-400 hover:text-[#E31E24] text-lg cursor-pointer">&times;</button>
            </div>
            @endif
            @endforeach
        @endif
    </div>
</div>

{{-- MODAL CREAR TIPO RETENCION --}}
<div id="modal-nuevo-tipo" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50" style="display:none">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 border-b-8 border-[#E31E24]">
        <h4 class="text-xs font-black italic tracking-tighter text-[#0a0a0a] mb-4">Nuevo Tipo de Retención</h4>
        <div class="space-y-3">
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Nombre <span class="text-[#E31E24]">*</span></label>
                <input type="text" id="nuevo-tipo-nombre"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Porcentaje</label>
                <input type="number" step="0.01" min="0" max="100" id="nuevo-tipo-porcentaje"
                    class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
            </div>
            <div class="flex items-center gap-2 pt-2">
                <button type="button" onclick="guardarNuevoTipo()"
                    class="px-5 py-2.5 bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-xs rounded-xl shadow-lg shadow-red-200 hover:shadow-xl transition-all cursor-pointer">Guardar</button>
                <button type="button" onclick="cerrarModalNuevoTipo()"
                    class="px-5 py-2.5 text-xs font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors cursor-pointer">Cancelar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let retIdx = {{ $factura ? $factura->retenciones->count() : 0 }};
    let distIdx = {{ $factura ? $factura->distribuciones->count() : 0 }};
    let porcentajesPorTipo = @json($tiposRetencion->pluck('porcentaje', 'id'));

    function recalcularValorRecibido() {
        const bruto = parseFloat(document.getElementById('valor_bruto').value) || 0;
        let totalDist = 0;
        document.querySelectorAll('#distribuciones-wrapper input[name$="[valor]"]').forEach(el => {
            const row = el.closest('.distribucion-row');
            const pctInput = row?.querySelector('input[name$="[porcentaje]"]');
            const pct = parseFloat(pctInput?.value) || 0;
            if (pct > 0) {
                totalDist += parseFloat(el.value) || 0;
            }
        });
        const vRecibido = bruto - totalDist;
        document.getElementById('valor_recibido').value = vRecibido.toFixed(2);

        const baseRet = document.querySelector('#retenciones-wrapper input[name$="[base_calculo]"]');
        if (baseRet) {
            baseRet.value = vRecibido.toFixed(2);
            baseRet.dispatchEvent(new Event('input'));
        }
    }

    let selectTarget = null;

    function configurarAutoCargaPorcentaje(container) {
        const select = container.querySelector('select[name$="[tipo_retencion_id]"]');
        if (!select) return;
        select.addEventListener('change', function() {
            if (this.value === '__new__') {
                selectTarget = this;
                abrirModalNuevoTipo();
                this.value = '';
                return;
            }
            const pct = porcentajesPorTipo[this.value] || 0;
            const pctInput = container.querySelector('input[name$="[porcentaje]"]');
            if (pctInput) {
                pctInput.value = pct;
                pctInput.dispatchEvent(new Event('input'));
            }
        });
    }

    function abrirModalNuevoTipo() {
        document.getElementById('nuevo-tipo-nombre').value = '';
        document.getElementById('nuevo-tipo-porcentaje').value = '';
        document.getElementById('modal-nuevo-tipo').style.display = 'flex';
    }

    function cerrarModalNuevoTipo() {
        document.getElementById('modal-nuevo-tipo').style.display = 'none';
    }

    function actualizarSelectsTipo(nuevoId, nuevoNombre) {
        document.querySelectorAll('select[name$="[tipo_retencion_id]"]').forEach(sel => {
            const exists = Array.from(sel.options).some(o => o.value == nuevoId);
            if (!exists) {
                const opt = document.createElement('option');
                opt.value = nuevoId;
                opt.text = nuevoNombre;
                sel.add(opt, sel.options[sel.options.length - 1]);
            }
        });
    }

    function guardarNuevoTipo() {
        const nombre = document.getElementById('nuevo-tipo-nombre').value.trim();
        const porcentaje = document.getElementById('nuevo-tipo-porcentaje').value;

        if (!nombre) { alert('El nombre es obligatorio'); return; }

        fetch('{{ route('tipos-retencion.store') }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ nombre, porcentaje: porcentaje || '0', slug: nombre.toLowerCase().replace(/\s+/g, '-') + '-' + Date.now(), activo: true })
        })
        .then(r => { if (!r.ok) { return r.json().then(e => { throw new Error(JSON.stringify(e)); }); } return r.json(); })
        .then(data => {
            if (data.id) {
                actualizarSelectsTipo(data.id, data.nombre);
                porcentajesPorTipo[data.id] = parseFloat(porcentaje) || 0;
                if (selectTarget) {
                    selectTarget.value = data.id;
                    const row = selectTarget.closest('.retencion-row, .distribucion-row');
                    const pctInput = row?.querySelector('input[name$="[porcentaje]"]');
                    if (pctInput) {
                        pctInput.value = porcentajesPorTipo[data.id];
                        pctInput.dispatchEvent(new Event('input'));
                    }
                }
                cerrarModalNuevoTipo();
            }
        })
        .catch(e => { alert('Error al crear tipo: ' + e.message); console.error(e); });
    }

    function configurarAutoCalculo(container) {
        container.querySelectorAll('input[name$="[porcentaje]"], input[name$="[base_calculo]"]').forEach(el => {
            el.addEventListener('input', function() {
                const row = this.closest('.retencion-row, .distribucion-row');
                if (!row) return;
                const pct = parseFloat(row.querySelector('input[name$="[porcentaje]"]')?.value) || 0;
                const base = parseFloat(row.querySelector('input[name$="[base_calculo]"]')?.value) || 0;
                const valorInput = row.querySelector('input[name$="[valor_retencion]"], input[name$="[valor]"]');
                if (valorInput && base > 0 && pct > 0) {
                    valorInput.value = (base * pct / 100).toFixed(2);
                }
                recalcularValorRecibido();
            });
        });
        container.querySelectorAll('input[name$="[valor_retencion]"], input[name$="[valor]"]').forEach(el => {
            el.addEventListener('input', recalcularValorRecibido);
        });
    }

    document.querySelectorAll('#retenciones-wrapper .retencion-row, #distribuciones-wrapper .distribucion-row').forEach(row => {
        configurarAutoCalculo(row);
        configurarAutoCargaPorcentaje(row);
    });

    document.getElementById('valor_bruto').addEventListener('input', function() {
        const bruto = parseFloat(this.value) || 0;
        document.querySelectorAll('#distribuciones-wrapper input[name$="[base_calculo]"]').forEach(el => {
            el.value = bruto.toFixed(2);
        });
        recalcularValorRecibido();
    });

    function addRetencion() {
        const wrapper = document.getElementById('retenciones-wrapper');
        const div = document.createElement('div');
        div.className = 'retencion-row flex items-end gap-2';
        div.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                <select name="retenciones[${retIdx}][tipo_retencion_id]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">Seleccione</option>
                    @foreach ($tiposRetencion as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                    <option value="__new__">➕ Nuevo...</option>
                </select>
            </div>
            <div class="w-20">
                <label class="block text-xs text-gray-500 mb-1">%</label>
                <input type="number" step="0.01" name="retenciones[${retIdx}][porcentaje]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="w-28">
                <label class="block text-xs text-gray-500 mb-1">Base (V. Recibido)</label>
                <input type="number" step="0.01" name="retenciones[${retIdx}][base_calculo]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none" readonly>
            </div>
            <div class="w-28">
                <label class="block text-xs text-gray-500 mb-1">Valor</label>
                <input type="number" step="0.01" name="retenciones[${retIdx}][valor_retencion]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="button" onclick="removeRetencion(this)"
                class="px-2 py-1.5 text-red-600 hover:text-red-800 text-lg cursor-pointer">&times;</button>
        `;
        wrapper.appendChild(div);
        configurarAutoCalculo(div);
        configurarAutoCargaPorcentaje(div);
        retIdx++;
    }

    function removeRetencion(btn) {
        btn.closest('.retencion-row').remove();
        recalcularValorRecibido();
    }

    function addDistribucion() {
        const wrapper = document.getElementById('distribuciones-wrapper');
        const div = document.createElement('div');
        div.className = 'distribucion-row flex items-end gap-2';
        div.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                <select name="distribuciones[${distIdx}][tipo_retencion_id]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none bg-white">
                    <option value="">Seleccione</option>
                    @foreach ($tiposRetencion as $t)
                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                    @endforeach
                    <option value="__new__">➕ Nuevo...</option>
                </select>
            </div>
            <div class="w-20">
                <label class="block text-xs text-gray-500 mb-1">%</label>
                <input type="number" step="0.01" min="0" max="100" name="distribuciones[${distIdx}][porcentaje]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="w-28">
                <label class="block text-xs text-gray-500 mb-1">Base (V. Bruto)</label>
                <input type="number" step="0.01" min="0" name="distribuciones[${distIdx}][base_calculo]" value="${parseFloat(document.getElementById('valor_bruto').value || 0).toFixed(2)}"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex-1">
                <label class="block text-xs text-gray-500 mb-1">Observación</label>
                <input type="text" name="distribuciones[${distIdx}][observacion]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="w-28">
                <label class="block text-xs text-gray-500 mb-1">Valor</label>
                <input type="number" step="0.01" name="distribuciones[${distIdx}][valor]"
                    class="w-full text-sm border border-gray-300 rounded-lg px-2 py-1.5 focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <button type="button" onclick="removeDistribucion(this)"
                class="px-2 py-1.5 text-red-600 hover:text-red-800 text-lg cursor-pointer">&times;</button>
        `;
        wrapper.appendChild(div);
        configurarAutoCalculo(div);
        distIdx++;
    }

    function removeDistribucion(btn) {
        btn.closest('.distribucion-row').remove();
        recalcularValorRecibido();
    }
</script>
@endpush

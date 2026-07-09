@php $socio ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Nombres <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="nombres" value="{{ old('nombres', $socio?->nombres) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
            required>
        @error('nombres') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Identificación <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="identificacion" value="{{ old('identificacion', $socio?->identificacion) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
            required>
        @error('identificacion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $socio?->telefono) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('telefono') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Email</label>
        <input type="email" name="email" value="{{ old('email', $socio?->email) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('email') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Dirección</label>
        <textarea name="direccion" rows="2"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">{{ old('direccion', $socio?->direccion) }}</textarea>
        @error('direccion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Cuota Mensual Base</label>
        <div class="relative">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-slate-400 text-sm font-bold">$</span>
            <input type="number" step="0.01" min="0" name="cuota_mensual_base"
                value="{{ old('cuota_mensual_base', $socio?->cuota_mensual_base) }}"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl pl-8 pr-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        </div>
        @error('cuota_mensual_base') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">% Participación</label>
        <div class="relative">
            <input type="number" step="0.01" min="0" max="100" name="porcentaje_participacion"
                value="{{ old('porcentaje_participacion', $socio?->porcentaje_participacion) }}"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 pr-8 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 text-sm font-bold">%</span>
        </div>
        @error('porcentaje_participacion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Tipo de Socio <span class="text-[#E31E24]">*</span></label>
        <select name="tipo_socio"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
            <option value="socio" {{ old('tipo_socio', $socio?->tipo_socio) == 'socio' ? 'selected' : '' }}>Socio</option>
            <option value="colaborador" {{ old('tipo_socio', $socio?->tipo_socio) == 'colaborador' ? 'selected' : '' }}>Colaborador</option>
        </select>
        @error('tipo_socio') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                {{ old('activo', $socio?->activo ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300 text-[#E31E24] focus:ring-[#E31E24]">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Activo</span>
        </label>
    </div>
</div>
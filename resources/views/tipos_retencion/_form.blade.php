@php $tipos_retencion ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Nombre <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="nombre" value="{{ old('nombre', $tipos_retencion?->nombre) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
        @error('nombre') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Slug</label>
        <input type="text" name="slug" value="{{ old('slug', $tipos_retencion?->slug) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('slug') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Porcentaje <span class="text-[#E31E24]">*</span></label>
        <div class="relative">
            <input type="number" step="0.01" min="0" max="100" name="porcentaje"
                value="{{ old('porcentaje', $tipos_retencion?->porcentaje) }}"
                class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 pr-8 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all" required>
            <span class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 text-sm font-bold">%</span>
        </div>
        @error('porcentaje') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5 block">Descripción</label>
        <textarea name="descripcion" rows="2"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">{{ old('descripcion', $tipos_retencion?->descripcion) }}</textarea>
        @error('descripcion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                {{ old('activo', $tipos_retencion?->activo ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300 text-[#E31E24] focus:ring-[#E31E24]">
            <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Activo</span>
        </label>
    </div>
</div>
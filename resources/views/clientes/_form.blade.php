@php $cliente ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Razón Social <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="razon_social" value="{{ old('razon_social', $cliente?->razon_social) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
            required>
        @error('razon_social') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">RUC <span class="text-[#E31E24]">*</span></label>
        <input type="text" name="ruc" value="{{ old('ruc', $cliente?->ruc) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
            required>
        @error('ruc') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Contacto</label>
        <input type="text" name="contacto" value="{{ old('contacto', $cliente?->contacto) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('contacto') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Teléfono</label>
        <input type="text" name="telefono" value="{{ old('telefono', $cliente?->telefono) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('telefono') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email', $cliente?->email) }}"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
        @error('email') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div></div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Dirección</label>
        <textarea name="direccion" rows="2"
            class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">{{ old('direccion', $cliente?->direccion) }}</textarea>
        @error('direccion') <p class="text-xs font-bold text-[#E31E24] mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex items-end pb-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="hidden" name="activo" value="0">
            <input type="checkbox" name="activo" value="1"
                {{ old('activo', $cliente?->activo ?? true) ? 'checked' : '' }}
                class="rounded border-slate-300 text-[#E31E24] focus:ring-[#E31E24]">
            <span class="text-xs font-black uppercase tracking-widest text-slate-500">Activo</span>
        </label>
    </div>
</div>

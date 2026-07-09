@php $usuario ??= null; @endphp

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $usuario?->name) }}"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
        @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
        <input type="email" name="email" value="{{ old('email', $usuario?->email) }}"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" required>
        @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Contraseña @if(!$usuario)<span class="text-red-500">*</span>@endif</label>
        <input type="password" name="password"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none" {{ !$usuario ? 'required' : '' }}>
        @if($usuario) <p class="text-xs text-gray-400 mt-1">Dejar vacío para mantener la actual.</p> @endif
        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Rol Principal</label>
        <select name="role_id"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="">Seleccione</option>
            @foreach ($roles as $r)
                <option value="{{ $r->id }}" {{ old('role_id', $usuario?->role_id) == $r->id ? 'selected' : '' }}>{{ $r->nombre }}</option>
            @endforeach
        </select>
        @error('role_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Socio Asociado</label>
        <select name="socio_id"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="">Seleccione</option>
            @foreach ($socios as $s)
                <option value="{{ $s->id }}" {{ old('socio_id', $usuario?->socio_id) == $s->id ? 'selected' : '' }}>{{ $s->nombres }}</option>
            @endforeach
        </select>
        @error('socio_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Activo</label>
        <select name="activo"
            class="w-full text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none bg-white">
            <option value="1" {{ old('activo', $usuario?->activo ?? true) ? 'selected' : '' }}>Sí</option>
            <option value="0" {{ old('activo', $usuario?->activo ?? true) ? '' : 'selected' }}>No</option>
        </select>
        @error('activo') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
    </div>
</div>

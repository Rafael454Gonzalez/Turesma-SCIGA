<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Turesma</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-[#0b0f1a] relative overflow-hidden selection:bg-[#E31E24] selection:text-white">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-[#E31E24] opacity-10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-[#E31E24] opacity-10 rounded-full blur-[120px]"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-[#facc15] opacity-5 rounded-full blur-[120px]"></div>
    </div>

    <div class="w-full max-w-md mx-4 relative z-10">
        <div class="bg-white rounded-3xl shadow-2xl p-8 border-b-8 border-[#E31E24]">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-[#E31E24] rounded-2xl shadow-lg shadow-red-200 mb-4">
                    <span class="text-white text-2xl font-black italic">TF</span>
                </div>
                <h1 class="text-2xl font-black italic tracking-tighter text-gray-900">Turesma</h1>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mt-2">Sistema de Control de Ingresos y Gastos Administrativos</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-100 text-red-600 text-sm font-bold rounded-xl px-4 py-3 mb-6">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <form method="POST" action="/login">
                @csrf

                <div class="mb-5">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Correo electrónico</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3.5 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
                        placeholder="admin@admin.com" required autofocus>
                </div>

                <div class="mb-6">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Contraseña</label>
                    <input type="password" name="password"
                        class="w-full bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-3.5 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all"
                        placeholder="••••••••" required>
                </div>

                <button type="submit"
                    class="w-full bg-[#E31E24] hover:bg-black text-white font-black uppercase tracking-widest text-sm py-3.5 px-4 rounded-xl shadow-lg shadow-red-200 transition-all hover:shadow-xl cursor-pointer active:scale-[0.98]">
                    Iniciar Sesión
                </button>
            </form>

            <p class="text-center text-[10px] font-black uppercase tracking-widest text-gray-300 mt-6">Turesma Facturación v1.0</p>
        </div>
    </div>
</body>
</html>

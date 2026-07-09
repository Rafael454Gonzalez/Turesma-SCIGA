<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Control de Ingresos y Gastos Administrativos')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .sidebar-collapsed .nav-text { display: none; }
        .sidebar-collapsed .nav-label { display: none; }
        .sidebar-collapsed .sidebar-header h1 { display: none; }
        .sidebar-collapsed .sidebar-header p { display: none; }
        .sidebar-collapsed .sidebar-user { display: none; }
        .sidebar-collapsed .logout-text { display: none; }
        .sidebar-collapsed { width: 4rem !important; }
        .sidebar-collapsed nav a, .sidebar-collapsed .logout-btn { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .toggle-btn { justify-content: center; }
        #sidebar { transition: width 0.2s ease; }
        #sidebar nav a { white-space: nowrap; }
        .sidebar-collapsed nav a span:first-child { font-size: 1.25rem; }
    </style>
</head>
<body class="bg-[#f8f9fa] min-h-screen flex selection:bg-[#E31E24] selection:text-white">
    {{-- SIDEBAR --}}
    <aside id="sidebar" class="w-64 bg-[#0b0f1a] text-white flex flex-col shrink-0 min-h-screen">
        <div class="sidebar-header px-5 py-5 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h1 class="text-base font-black italic tracking-tighter text-white">Turesma</h1>
                <p class="sidebar-user text-[10px] font-black uppercase tracking-widest text-gray-500 mt-1">Facturación</p>
            </div>
            <button onclick="toggleSidebar()" class="toggle-btn text-gray-500 hover:text-[#E31E24] p-1 rounded cursor-pointer text-lg leading-none transition-colors">&larr;</button>
        </div>

        <nav class="flex-1 py-4 px-3 space-y-1 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('dashboard') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>📊</span><span class="nav-text">Dashboard</span>
            </a>
            <a href="/socios" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('socios*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>👥</span><span class="nav-text">Socios</span>
            </a>
            <a href="/clientes" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('clientes*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>🏢</span><span class="nav-text">Clientes</span>
            </a>
            <a href="/facturas" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('facturas*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>📄</span><span class="nav-text">Facturas</span>
            </a>
            <a href="/liquidaciones" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('liquidaciones*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>📋</span><span class="nav-text">Liquidaciones</span>
            </a>
            <a href="/tipos-retencion" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('tipos-retencion*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <span>🏷️</span><span class="nav-text">Retenciones</span>
            </a>

            <div class="pt-4">
                <p class="nav-label text-[10px] font-black uppercase tracking-widest text-gray-600 px-3 mb-2">Caja</p>
                <a href="/caja/movimientos" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('caja/movimientos*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span>💰</span><span class="nav-text">Movimientos</span>
                </a>
                <a href="/caja/aportes" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('caja/aportes*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span>🤝</span><span class="nav-text">Aportes Socios</span>
                </a>
            </div>

            <div class="pt-4 mt-2 border-t border-gray-800">
                <a href="/usuarios" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('usuarios*') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span>👥</span><span class="nav-text">Usuarios</span>
                </a>
                <a href="/configuracion" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all {{ request()->is('configuracion') ? 'bg-[#E31E24] text-white shadow-lg shadow-red-700/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                    <span>⚙️</span><span class="nav-text">Configuración</span>
                </a>
            </div>
        </nav>

        <div class="px-3 py-4 border-t border-gray-800">
            <p class="sidebar-user text-[10px] font-black uppercase tracking-widest text-gray-500 px-3 mb-2">{{ auth()->user()->name }}</p>
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-gray-400 hover:bg-[#E31E24] hover:text-white transition-all cursor-pointer">
                    <span>🚪</span><span class="logout-text">Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 overflow-y-auto bg-[#f8f9fa]">
        <div class="p-6 lg:p-8">
            @yield('content')
        </div>
    </main>

    @push('scripts')
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const btn = document.querySelector('.toggle-btn');
            sidebar.classList.toggle('sidebar-collapsed');
            const collapsed = sidebar.classList.contains('sidebar-collapsed');
            btn.innerHTML = collapsed ? '&rarr;' : '&larr;';
            localStorage.setItem('sidebarCollapsed', collapsed);
        }
        if (localStorage.getItem('sidebarCollapsed') === 'true') {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.add('sidebar-collapsed');
            document.querySelector('.toggle-btn').innerHTML = '&rarr;';
        }
    </script>
    @endpush
    @stack('scripts')
</body>
</html>

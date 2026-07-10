@extends('layouts.master')

@section('title', 'Dashboard Ejecutivo')

@section('content')
    {{-- HEADER --}}
    <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">Dashboard Ejecutivo</h2>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mt-1">{{ $periodoActual }}</p>
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" class="flex items-center gap-3">
                <select name="mes" onchange="this.form.submit()"
                    class="bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                    <option value="">Todos los meses</option>
                    @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
                    @foreach ($mesesDisponibles as $m)
                        <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] ?? $m }}</option>
                    @endforeach
                </select>
                <select name="anio" onchange="this.form.submit()"
                    class="bg-slate-50 border-2 border-slate-100 rounded-xl px-4 py-2.5 text-sm font-bold text-slate-700 outline-none focus:border-[#E31E24] transition-all">
                    <option value="">Todos los años</option>
                    @foreach ($aniosDisponibles as $a)
                        <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
                @if (request()->hasAny(['mes', 'anio']))
                    <a href="{{ route('dashboard') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-[#E31E24] transition-colors">Limpiar</a>
                @endif
            </form>
            <div class="relative" id="reportes-dropdown">
                <button onclick="toggleReportes()"
                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-[#E31E24] hover:text-white hover:border-[#E31E24] transition-all">
                    <span>📄</span>
                    <span>Reportes</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"/></svg>
                </button>
                <div id="reportes-menu" class="hidden absolute right-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50">
                    @php
                        $mesSel = request('mes');
                        $anioSel = request('anio') ?? now()->year;
                    @endphp

                    @if ($mesSel)
                    <div class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Reporte Mensual</div>
                    <a href="{{ route('reportes.mensual', ['anio' => $anioSel, 'mes' => $mesSel]) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>👁️</span> Vista Previa
                    </a>
                    <a href="{{ route('reportes.mensual', ['anio' => $anioSel, 'mes' => $mesSel, 'mode' => 'pdf']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>📄</span> Descargar PDF
                    </a>
                    <a href="{{ route('reportes.mensual', ['anio' => $anioSel, 'mes' => $mesSel, 'mode' => 'print']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors" target="_blank">
                        <span>🖨️</span> Imprimir
                    </a>
                    @endif

                    @if ($anioSel)
                    <div class="border-t border-slate-100 mt-1"></div>
                    <div class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Reporte Anual</div>
                    <a href="{{ route('reportes.anual', ['anio' => $anioSel]) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>👁️</span> Vista Previa
                    </a>
                    <a href="{{ route('reportes.anual', ['anio' => $anioSel, 'mode' => 'pdf']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>📄</span> Descargar PDF
                    </a>
                    @endif

                    <div class="border-t border-slate-100 mt-1"></div>
                    <div class="px-4 py-2 text-[9px] font-black uppercase tracking-widest text-slate-400 border-b border-slate-100">Situación Financiera</div>
                    <a href="{{ route('reportes.situacion-financiera') }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>👁️</span> Vista Previa
                    </a>
                    <a href="{{ route('reportes.situacion-financiera', ['mode' => 'pdf']) }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:text-[#E31E24] transition-colors">
                        <span>📄</span> Descargar PDF
                    </a>
                </div>
            </div>
            <script>
                function toggleReportes() {
                    var menu = document.getElementById('reportes-menu');
                    menu.classList.toggle('hidden');
                }
                document.addEventListener('click', function(e) {
                    var dd = document.getElementById('reportes-dropdown');
                    if (!dd.contains(e.target)) {
                        document.getElementById('reportes-menu').classList.add('hidden');
                    }
                });
            </script>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4 mb-8">
        {{-- Valor Recibido --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Valor Recibido</span>
                <span class="text-lg">💰</span>
            </div>
            <p class="text-2xl font-black italic tracking-tighter text-[#16a34a]">$ {{ number_format($stats['valor_recibido'], 2) }}</p>
        </div>

        {{-- Total Gastos --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Gastos</span>
                <span class="text-lg">💸</span>
            </div>
            <p class="text-2xl font-black italic tracking-tighter text-[#dc2626]">$ {{ number_format($stats['total_gastos'], 2) }}</p>
        </div>

        {{-- Total Dinero --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Total Dinero</span>
                <span class="text-lg">🏦</span>
            </div>
            <p class="text-2xl font-black italic tracking-tighter text-[#0a0a0a]">$ {{ number_format($stats['total_dinero'], 2) }}</p>
        </div>

        {{-- Facturas Emitidas --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Facturas Emitidas</span>
                <span class="text-lg">📄</span>
            </div>
            <p class="text-2xl font-black italic tracking-tighter text-[#2563eb]">{{ $stats['facturas_emitidas'] }}</p>
        </div>

        {{-- Socios Activos --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 hover:shadow-md transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Socios Activos</span>
                <span class="text-lg">👥</span>
            </div>
            <p class="text-2xl font-black italic tracking-tighter text-[#E31E24]">{{ $stats['socios_activos'] }}</p>
            <p class="text-[10px] font-black uppercase tracking-widest mt-1 text-slate-400">Registrados en el sistema</p>
        </div>

    </div>

    @php
        $cats = [];
        $seriesVR = [];
        $seriesTG = [];
        $seriesTD = [];
        foreach ($cierres as $c) {
            $cats[] = ($mesNombres[$c->mes] ?? $c->mes) . ' ' . $c->anio;
            $seriesVR[] = (float) $c->valor_recibido;
            $seriesTG[] = (float) $c->total_gastos;
            $seriesTD[] = (float) $c->total_dinero;
        }

        $topSociosNames = $topSocios->pluck('socio.nombres')->toArray();
        $topSociosValues = $topSocios->pluck('total')->map(fn($v) => (float) $v)->toArray();

        $compUltimos = $cierres->slice(-6);
        $compCats = [];
        $compVR = [];
        $compTD = [];
        foreach ($compUltimos as $c) {
            $compCats[] = ($mesNombres[$c->mes] ?? $c->mes) . ' ' . $c->anio;
            $compVR[] = (float) $c->valor_recibido;
            $compTD[] = (float) $c->total_dinero;
        }

        $todosCierres = $cierres->map(fn($c) => [
            'mes' => $c->mes,
            'anio' => $c->anio,
            'valor_recibido' => (float) $c->valor_recibido,
            'total_gastos' => (float) $c->total_gastos,
            'saldo' => (float) $c->saldo,
            'cuota_administrativa_total' => (float) $c->cuota_administrativa_total,
            'retencion_total' => (float) $c->retencion_total,
            'total_dinero' => (float) $c->total_dinero,
        ])->values();
    @endphp

    {{-- CARRUSEL DE GRÁFICOS --}}
    <div id="charts-wrapper" class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 mb-8 relative">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <button id="btn-prev-chart" class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-[#E31E24] transition-all text-slate-400 hover:text-[#E31E24] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <span id="chart-indicator" class="text-[10px] font-black uppercase tracking-widest text-slate-400 min-w-[2rem] text-center"></span>
                <button id="btn-next-chart" class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-[#E31E24] transition-all text-slate-400 hover:text-[#E31E24] cursor-pointer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                </button>
                <button id="btn-toggle-view" class="w-8 h-8 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 hover:border-[#E31E24] transition-all text-slate-400 hover:text-[#E31E24] cursor-pointer" title="Ver en cuadrícula">
                    <svg id="icon-grid" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10 0a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/></svg>
                    <svg id="icon-carousel" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"/></svg>
                </button>
            </div>
            <h3 id="chart-title" class="text-xs font-black italic tracking-tighter text-[#0a0a0a]"></h3>
            <div id="chart-filters" class="flex items-center gap-2"></div>
        </div>
        <div id="chart-container" class="min-h-[300px]">
            <div class="chart-slide" data-title="Evolución Mensual">
                <div class="slide-header flex items-center justify-between mb-4" style="display:none">
                    <h3 class="slide-title text-xs font-black italic tracking-tighter text-[#0a0a0a]">Evolución Mensual</h3>
                    <div class="flex items-center gap-2" data-chart-filters="evolucion">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Año</span>
                        <select class="chart-anio text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Todos</option>
                            @foreach ($aniosDisponibles as $a)
                                <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="chart-evolucion"></div>
            </div>
            <div class="chart-slide" data-title="Distribución del Dinero">
                <div class="slide-header flex items-center justify-between mb-4" style="display:none">
                    <h3 class="slide-title text-xs font-black italic tracking-tighter text-[#0a0a0a]">Distribución del Dinero</h3>
                    <div class="flex items-center gap-2" data-chart-filters="distribucion">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Filtrar</span>
                        <select class="chart-mes text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Mes</option>
                            @foreach ($mesesDisponibles as $m)
                                <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] ?? $m }}</option>
                            @endforeach
                        </select>
                        <select class="chart-anio text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Año</option>
                            @foreach ($aniosDisponibles as $a)
                                <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="chart-distribucion"></div>
            </div>
            <div class="chart-slide" data-title="Top Socios por Valor Recibido">
                <div class="slide-header flex items-center justify-between mb-4" style="display:none">
                    <h3 class="slide-title text-xs font-black italic tracking-tighter text-[#0a0a0a]">Top Socios por Valor Recibido</h3>
                    <div class="flex items-center gap-2" data-chart-filters="top">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Filtrar</span>
                        <select class="chart-mes text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Mes</option>
                            @foreach ($mesesDisponibles as $m)
                                <option value="{{ $m }}" {{ request('mes') == $m ? 'selected' : '' }}>{{ $mesNombres[$m] ?? $m }}</option>
                            @endforeach
                        </select>
                        <select class="chart-anio text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Año</option>
                            @foreach ($aniosDisponibles as $a)
                                <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="chart-top" style="height: 350px; width: 100%;"></div>
            </div>
            <div class="chart-slide" data-title="Comparativa Mensual">
                <div class="slide-header flex items-center justify-between mb-4" style="display:none">
                    <h3 class="slide-title text-xs font-black italic tracking-tighter text-[#0a0a0a]">Comparativa Mensual</h3>
                    <div class="flex items-center gap-2" data-chart-filters="comparativa">
                        <span class="text-[9px] font-black uppercase tracking-widest text-slate-400">Año</span>
                        <select class="chart-anio text-[9px] font-bold border border-slate-200 rounded-lg px-2 py-1 outline-none focus:border-[#E31E24] bg-white text-slate-600">
                            <option value="">Todos</option>
                            @foreach ($aniosDisponibles as $a)
                                <option value="{{ $a }}" {{ request('anio') == $a ? 'selected' : '' }}>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div id="chart-comparativa"></div>
            </div>
        </div>

        <div id="chart-dots" class="flex items-center justify-center gap-1.5 mt-4">
            <button data-index="0" class="chart-dot w-2 h-2 rounded-full bg-[#E31E24] border-0 cursor-pointer transition-all"></button>
            <button data-index="1" class="chart-dot w-2 h-2 rounded-full bg-slate-300 border-0 cursor-pointer transition-all hover:bg-slate-400"></button>
            <button data-index="2" class="chart-dot w-2 h-2 rounded-full bg-slate-300 border-0 cursor-pointer transition-all hover:bg-slate-400"></button>
            <button data-index="3" class="chart-dot w-2 h-2 rounded-full bg-slate-300 border-0 cursor-pointer transition-all hover:bg-slate-400"></button>
        </div>
    </div>

    {{-- ApexCharts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var cierresData = @json($todosCierres);
            var topSociosData = @json($todosTopSocios);
            var mesesDisp = @json($mesesDisponibles);
            var aniosDisp = @json($aniosDisponibles);

            var mesNombres = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
            function monthName(m) {
                return mesNombres[m] || '';
            }

            function filterCierres(mes, anio) {
                return cierresData.filter(function(c) {
                    if (mes && c.mes !== mes) return false;
                    if (anio && c.anio !== anio) return false;
                    return true;
                });
            }

            function sumCierres(lista) {
                var sum = { valor_recibido: 0, total_gastos: 0, saldo: 0, cuota_administrativa_total: 0, retencion_total: 0, total_dinero: 0 };
                lista.forEach(function(c) {
                    sum.valor_recibido += c.valor_recibido;
                    sum.total_gastos += c.total_gastos;
                    sum.saldo += c.saldo;
                    sum.cuota_administrativa_total += c.cuota_administrativa_total;
                    sum.retencion_total += c.retencion_total;
                    sum.total_dinero += c.total_dinero;
                });
                return sum;
            }

            function filterTopSocios(mes, anio) {
                var grouped = {};
                topSociosData.forEach(function(f) {
                    if (mes && f.mes !== mes) return;
                    if (anio && f.anio !== anio) return;
                    if (!grouped[f.socio]) grouped[f.socio] = 0;
                    grouped[f.socio] += f.total;
                });
                var sorted = Object.entries(grouped).sort(function(a, b) { return b[1] - a[1]; }).slice(0, 10);
                return { names: sorted.map(function(s) { return s[0]; }), values: sorted.map(function(s) { return s[1]; }) };
            }

            function chartBaseOpts(type, height) {
                return {
                    chart: {
                        type: type,
                        height: height,
                        toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } },
                        fontFamily: 'Arial, sans-serif',
                        foreColor: '#94a3b8',
                    },
                };
            }

            // ---- Evolución ----
            function renderEvolucion(mes, anio) {
                var filtered = filterCierres(mes, anio);
                if (filtered.length === 0) {
                    document.querySelector('#chart-evolucion').innerHTML = '<div class="flex items-center justify-center" style="height:300px"><p class="text-sm font-bold text-slate-300">📭 Sin datos</p></div>';
                    return;
                }
                var cats = filtered.map(function(c) { return monthName(c.mes) + ' ' + c.anio; });
                var vr = filtered.map(function(c) { return c.valor_recibido; });
                var tg = filtered.map(function(c) { return c.total_gastos; });
                var td = filtered.map(function(c) { return c.total_dinero; });
                var opts = Object.assign(chartBaseOpts('line', 300), {
                    series: [
                        { name: 'Valor Recibido', data: vr },
                        { name: 'Gastos', data: tg },
                        { name: 'Total Dinero', data: td },
                    ],
                    xaxis: { categories: cats, labels: { style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' } } },
                    yaxis: { labels: { formatter: function (v) { return '$ ' + v.toFixed(0); }, style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' } } },
                    colors: ['#16a34a', '#dc2626', '#0f172a'],
                    stroke: { curve: 'smooth', width: 2.5 },
                    markers: { size: 4, strokeColors: '#fff', strokeWidth: 2, hover: { size: 6 } },
                    tooltip: { y: { formatter: function (v) { return '$ ' + v.toFixed(2); } }, style: { fontSize: '12px', fontFamily: 'Arial' } },
                    grid: { borderColor: '#f1f5f9', padding: { left: 10, right: 10 } },
                    legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', fontWeight: 700, labels: { colors: '#475569' }, markers: { width: 10, height: 10, radius: 2 } },
                    dataLabels: { enabled: false },
                });
                chartInstances['evolucion'] = new ApexCharts(document.querySelector('#chart-evolucion'), opts);
                chartInstances['evolucion'].render();
            }

            // ---- Distribución ----
            function renderDistribucion(mes, anio) {
                var filtered = filterCierres(mes, anio);
                if (filtered.length === 0) {
                    document.querySelector('#chart-distribucion').innerHTML = '<div class="flex items-center justify-center" style="height:300px"><p class="text-sm font-bold text-slate-300">📭 Sin datos</p></div>';
                    return;
                }
                var sum = sumCierres(filtered);
                var total = parseFloat((sum.total_gastos + sum.cuota_administrativa_total + sum.retencion_total + sum.saldo).toFixed(2));
                var series = [sum.total_gastos, sum.cuota_administrativa_total, sum.retencion_total, sum.saldo].map(function(v) { return parseFloat(v.toFixed(2)); });
                var opts = {
                    chart: { type: 'donut', height: 300, toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } }, fontFamily: 'Arial, sans-serif', foreColor: '#94a3b8' },
                    series: series,
                    labels: ['Gastos', 'Cuota Administrativa', 'Retenciones', 'Saldo'],
                    colors: ['#dc2626', '#f59e0b', '#6366f1', '#16a34a'],
                    plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'Total', formatter: function () { return '$ ' + total.toFixed(2); }, fontSize: '11px', fontWeight: 700, color: '#0f172a' } } } } },
                    stroke: { show: false },
                    dataLabels: { enabled: true, formatter: function (v) { return v.toFixed(1) + '%'; }, style: { fontSize: '10px', fontWeight: 700, colors: ['#fff'] }, dropShadow: { enabled: false } },
                    legend: { position: 'bottom', fontSize: '10px', fontWeight: 700, labels: { colors: '#475569' }, markers: { width: 10, height: 10, radius: 2 }, itemMargin: { horizontal: 12 } },
                    tooltip: { y: { formatter: function (v) { return '$ ' + v.toFixed(2); } } },
                    responsive: [{ breakpoint: 640, options: { chart: { height: 250 }, legend: { position: 'bottom' } } }],
                };
                chartInstances['distribucion'] = new ApexCharts(document.querySelector('#chart-distribucion'), opts);
                chartInstances['distribucion'].render();
            }

            // ---- Top Socios ----
            function renderTopSocios(mes, anio) {
                var data = filterTopSocios(mes, anio);
                if (data.names.length === 0) {
                    document.querySelector('#chart-top').innerHTML = '<div class="flex items-center justify-center" style="height:350px"><p class="text-sm font-bold text-slate-300">📭 Sin facturas en este período</p></div>';
                    return;
                }
                var opts = {
                    chart: { type: 'bar', height: 350, toolbar: { show: true, tools: { download: true, selection: false, zoom: false, zoomin: false, zoomout: false, pan: false, reset: false } }, fontFamily: 'Arial, sans-serif', foreColor: '#94a3b8' },
                    series: [{ name: 'Valor Recibido', data: data.values }],
                    xaxis: { categories: data.names, labels: { style: { fontSize: '10px', fontWeight: 700, colors: '#475569' } } },
                    yaxis: { labels: { formatter: function (v) { if (typeof v === 'number') return '$ ' + v.toFixed(0); return v; }, style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' } } },
                    plotOptions: { bar: { horizontal: true, borderRadius: 4, dataLabels: { position: 'top' } } },
                    colors: ['#E31E24'],
                    fill: { opacity: 0.85 },
                    dataLabels: { enabled: true, formatter: function (v) { return '$ ' + Number(v).toLocaleString('en-US'); }, style: { fontSize: '11px', fontWeight: 800, colors: ['#0f172a'] }, offsetX: 5 },
                    tooltip: { y: { formatter: function (v) { return '$ ' + v.toFixed(2); } }, style: { fontSize: '12px', fontFamily: 'Arial' } },
                    grid: { borderColor: '#f1f5f9', padding: { left: 0, right: 30 } },
                    legend: { show: false },
                };
                chartInstances['top'] = new ApexCharts(document.querySelector('#chart-top'), opts);
                chartInstances['top'].render();
            }

            // ---- Comparativa ----
            function renderComparativa(mes, anio) {
                console.log('renderComparativa -> mes=' + mes + ' anio=' + anio);
                var filtered = filterCierres(mes, anio);
                if (filtered.length === 0) {
                    document.querySelector('#chart-comparativa').innerHTML = '<div class="flex items-center justify-center" style="height:300px"><p class="text-sm font-bold text-slate-300">📭 Sin datos</p></div>';
                    return;
                }
                var cats = filtered.map(function(c) { return monthName(c.mes) + ' ' + c.anio; });
                var vr = filtered.map(function(c) { return c.valor_recibido; });
                var td = filtered.map(function(c) { return c.total_dinero; });
                var opts = Object.assign(chartBaseOpts('bar', 300), {
                    series: [
                        { name: 'Valor Recibido', data: vr },
                        { name: 'Total Dinero', data: td },
                    ],
                    xaxis: { categories: cats, labels: { style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' } } },
                    yaxis: { labels: { formatter: function (v) { return '$ ' + v.toFixed(0); }, style: { fontSize: '10px', fontWeight: 700, colors: '#94a3b8' } } },
                    plotOptions: { bar: { horizontal: false, borderRadius: 4, columnWidth: '60%' } },
                    colors: ['#16a34a', '#0f172a'],
                    fill: { opacity: [0.9, 0.7] },
                    dataLabels: { enabled: false },
                    tooltip: { y: { formatter: function (v) { return '$ ' + v.toFixed(2); } }, style: { fontSize: '12px', fontFamily: 'Arial' } },
                    grid: { borderColor: '#f1f5f9', padding: { left: 10, right: 10 } },
                    legend: { position: 'top', horizontalAlign: 'right', fontSize: '10px', fontWeight: 700, labels: { colors: '#475569' }, markers: { width: 10, height: 10, radius: 2 } },
                });
                chartInstances['comparativa'] = new ApexCharts(document.querySelector('#chart-comparativa'), opts);
                chartInstances['comparativa'].render();
            }

            // ---- Renderear gráfica individual por nombre ----
            var chartRenderers = {
                evolucion: renderEvolucion,
                distribucion: renderDistribucion,
                top: renderTopSocios,
                comparativa: renderComparativa,
            };
            var chartInstances = {};

            // ---- Eventos ----
            document.querySelectorAll('[data-chart-filters]').forEach(function(group) {
                var chartName = group.getAttribute('data-chart-filters');
                var mesEl = group.querySelector('.chart-mes');
                var anioEl = group.querySelector('.chart-anio');
                function redraw() {
                    var mes = mesEl ? parseInt(mesEl.value) || null : null;
                    var anio = anioEl ? parseInt(anioEl.value) || null : null;
                    var id = 'chart-' + chartName;
                    if (chartInstances[chartName]) {
                        chartInstances[chartName].destroy();
                        delete chartInstances[chartName];
                    }
                    document.querySelector('#' + id).innerHTML = '';
                    if (chartRenderers[chartName]) chartRenderers[chartName](mes, anio);
                }
                if (mesEl) mesEl.addEventListener('change', redraw);
                if (anioEl) anioEl.addEventListener('change', redraw);
            });

            // ---- Inicio ----
            var mainMes = document.querySelector('select[name="mes"]');
            var mainAnio = document.querySelector('select[name="anio"]');
            // Iniciar cada gráfico con sus propios valores (vacío = todos)
            document.querySelectorAll('[data-chart-filters]').forEach(function(group) {
                var chartName = group.getAttribute('data-chart-filters');
                var mesEl = group.querySelector('.chart-mes');
                var anioEl = group.querySelector('.chart-anio');
                var mes = mesEl ? parseInt(mesEl.value) || null : null;
                var anio = anioEl ? parseInt(anioEl.value) || null : null;
                var id = 'chart-' + chartName;
                console.log('Init ' + chartName + ': mes=' + mes + ' anio=' + anio);
                document.querySelector('#' + id).innerHTML = '';
                if (chartRenderers[chartName]) chartRenderers[chartName](mes, anio);
            });

            // ---- Carrusel / Grid ----
            var currentChart = 0;
            var totalCharts = 4;
            var mode = 'grid';
            var chartNames = ['evolucion', 'distribucion', 'top', 'comparativa'];

            function showChart(index) {
                if (mode === 'grid') return;
                var slides = document.querySelectorAll('.chart-slide');
                slides.forEach(function(s, i) {
                    s.style.display = i === index ? 'block' : 'none';
                });
                document.getElementById('chart-title').textContent = slides[index].getAttribute('data-title');
                document.getElementById('chart-indicator').textContent = (index + 1) + '/' + totalCharts;
                var dots = document.querySelectorAll('.chart-dot');
                dots.forEach(function(d, i) {
                    d.style.backgroundColor = i === index ? '#E31E24' : '#cbd5e1';
                });
                var filtersContainer = document.getElementById('chart-filters');
                var prevFilter = filtersContainer.querySelector('[data-chart-filters]');
                if (prevFilter) slides[currentChart].appendChild(prevFilter);
                var newFilter = slides[index].querySelector('[data-chart-filters]');
                if (newFilter) filtersContainer.appendChild(newFilter);
                currentChart = index;
            }

            function getFilterValues(chartName) {
                var group = document.querySelector('[data-chart-filters="' + chartName + '"]');
                if (!group) return { mes: null, anio: null };
                var mesEl = group.querySelector('.chart-mes');
                var anioEl = group.querySelector('.chart-anio');
                return {
                    mes: mesEl ? parseInt(mesEl.value) || null : null,
                    anio: anioEl ? parseInt(anioEl.value) || null : null
                };
            }

            function reRenderChart(chartName) {
                var f = getFilterValues(chartName);
                var id = 'chart-' + chartName;
                var el = document.querySelector('#' + id);
                if (el) {
                    if (chartInstances[chartName]) {
                        chartInstances[chartName].destroy();
                        delete chartInstances[chartName];
                    }
                    el.innerHTML = '';
                    if (chartRenderers[chartName]) chartRenderers[chartName](f.mes, f.anio);
                }
            }

            function initGrid() {
                var slides = document.querySelectorAll('.chart-slide');
                var wrapper = document.getElementById('charts-wrapper');
                var container = document.getElementById('chart-container');
                document.getElementById('btn-prev-chart').style.display = 'none';
                document.getElementById('btn-next-chart').style.display = 'none';
                document.getElementById('chart-indicator').style.display = 'none';
                document.getElementById('chart-dots').style.display = 'none';
                document.getElementById('chart-title').style.display = 'none';
                document.getElementById('chart-filters').style.display = 'none';
                document.getElementById('icon-grid').classList.add('hidden');
                document.getElementById('icon-carousel').classList.remove('hidden');
                document.getElementById('btn-toggle-view').setAttribute('title', 'Ver en carrusel');
                wrapper.classList.remove('p-5');
                wrapper.classList.add('p-0', 'bg-transparent', 'border-0', 'shadow-none');
                container.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-2', 'gap-6');
                slides.forEach(function(s) {
                    s.style.display = 'block';
                    s.classList.add('bg-white', 'rounded-2xl', 'shadow-sm', 'border', 'border-slate-200', 'p-5');
                    var header = s.querySelector('.slide-header');
                    if (header) header.style.display = 'flex';
                });
                setTimeout(function() { chartNames.forEach(reRenderChart); }, 50);
            }

            function toggleMode() {
                var slides = document.querySelectorAll('.chart-slide');
                var wrapper = document.getElementById('charts-wrapper');
                var container = document.getElementById('chart-container');
                var isCarousel = mode === 'carousel';

                if (isCarousel) {
                    mode = 'grid';
                    document.getElementById('btn-prev-chart').style.display = 'none';
                    document.getElementById('btn-next-chart').style.display = 'none';
                    document.getElementById('chart-indicator').style.display = 'none';
                    document.getElementById('chart-dots').style.display = 'none';
                    document.getElementById('chart-title').style.display = 'none';
                    document.getElementById('chart-filters').style.display = 'none';
                    document.getElementById('icon-grid').classList.add('hidden');
                    document.getElementById('icon-carousel').classList.remove('hidden');
                    document.getElementById('btn-toggle-view').setAttribute('title', 'Ver en carrusel');
                    wrapper.classList.remove('p-5');
                    wrapper.classList.add('p-0', 'bg-transparent', 'border-0', 'shadow-none');
                    container.classList.add('grid', 'grid-cols-1', 'lg:grid-cols-2', 'gap-6');

                    var filtersContainer = document.getElementById('chart-filters');
                    var movedFilter = filtersContainer.querySelector('[data-chart-filters]');
                    if (movedFilter) slides[currentChart].appendChild(movedFilter);

                    slides.forEach(function(s) {
                        s.style.display = 'block';
                        s.classList.add('bg-white', 'rounded-2xl', 'shadow-sm', 'border', 'border-slate-200', 'p-5');
                        var header = s.querySelector('.slide-header');
                        if (header) header.style.display = 'flex';
                    });

                    setTimeout(function() { chartNames.forEach(reRenderChart); }, 50);
                } else {
                    mode = 'carousel';
                    document.getElementById('btn-prev-chart').style.display = '';
                    document.getElementById('btn-next-chart').style.display = '';
                    document.getElementById('chart-indicator').style.display = '';
                    document.getElementById('chart-dots').style.display = '';
                    document.getElementById('chart-title').style.display = '';
                    document.getElementById('chart-filters').style.display = '';
                    document.getElementById('icon-grid').classList.remove('hidden');
                    document.getElementById('icon-carousel').classList.add('hidden');
                    document.getElementById('btn-toggle-view').setAttribute('title', 'Ver en cuadrícula');
                    wrapper.classList.remove('p-0', 'bg-transparent', 'border-0', 'shadow-none');
                    wrapper.classList.add('p-5');
                    container.classList.remove('grid', 'grid-cols-1', 'lg:grid-cols-2', 'gap-6');

                    slides.forEach(function(s) {
                        s.classList.remove('bg-white', 'rounded-2xl', 'shadow-sm', 'border', 'border-slate-200', 'p-5');
                        var header = s.querySelector('.slide-header');
                        if (header) header.style.display = 'none';
                    });

                    showChart(currentChart);
                    reRenderChart(chartNames[currentChart]);
                }
            }

            document.getElementById('btn-prev-chart').addEventListener('click', function() {
                showChart((currentChart - 1 + totalCharts) % totalCharts);
            });
            document.getElementById('btn-next-chart').addEventListener('click', function() {
                showChart((currentChart + 1) % totalCharts);
            });
            document.getElementById('btn-toggle-view').addEventListener('click', toggleMode);
            document.querySelectorAll('.chart-dot').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    showChart(parseInt(this.getAttribute('data-index')));
                });
            });

            initGrid();
        });
    </script>
@endsection
<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Socio;
use App\Models\Facturacion\Factura;
use App\Services\ReporteService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected ReporteService $reporteService
    ) {}

    public function index(Request $request)
    {
        $mesFiltro = $request->query('mes');
        $anioFiltro = $request->query('anio');

        $cierres = $this->reporteService->calcularCierres(null, null);

        $kpiCierre = $this->reporteService->determinarPeriodoKPI($cierres, $mesFiltro, $anioFiltro);
        $prevCierre = $this->reporteService->determinarPeriodoAnterior($cierres, $kpiCierre);

        $mostrarSuma = !$mesFiltro || !$anioFiltro;

        $stats = $this->calcularKPIs($kpiCierre, $prevCierre);

        $cierresFiltrados = $cierres
            ->when($mesFiltro, fn($c) => $c->where('mes', (int) $mesFiltro))
            ->when($anioFiltro, fn($c) => $c->where('anio', (int) $anioFiltro));

        if ($mostrarSuma) {
            $sumaCierre = (object) [
                'valor_recibido' => $cierresFiltrados->sum('valor_recibido'),
                'total_gastos' => $cierresFiltrados->sum('total_gastos'),
                'total_dinero' => $cierresFiltrados->sum('total_dinero'),
            ];
            $stats['valor_recibido'] = $sumaCierre->valor_recibido;
            $stats['total_gastos'] = $sumaCierre->total_gastos;
            $stats['total_dinero'] = $sumaCierre->total_dinero;
            $stats['facturas_emitidas'] = Factura::when($anioFiltro, fn($q) => $q->whereYear('fecha_emision', $anioFiltro))
                ->when($mesFiltro, fn($q) => $q->whereMonth('fecha_emision', $mesFiltro))->count();
            $stats['socios_activos'] = Socio::where('activo', true)->count();
            unset($stats['var_valor_recibido'], $stats['var_total_gastos'], $stats['var_total_dinero'], $stats['var_facturas']);
        }

        $mesNombres = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $periodoActual = $kpiCierre
            ? ($mostrarSuma
                ? 'Todos los períodos'
                : ($mesNombres[$kpiCierre->mes] ?? $kpiCierre->mes) . ' ' . $kpiCierre->anio)
            : 'Sin datos';

        $mesesDisponibles = $this->reporteService->obtenerMesesDisponibles($anioFiltro);
        $aniosDisponibles = $this->reporteService->obtenerAniosDisponibles($mesFiltro);

        $topSocios = Factura::selectRaw('socio_id, SUM(valor_recibido) as total')
            ->when($anioFiltro, fn($q) => $q->whereYear('fecha_emision', $anioFiltro))
            ->when($mesFiltro, fn($q) => $q->whereMonth('fecha_emision', $mesFiltro))
            ->groupBy('socio_id')
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->load('socio');

        $todosTopSocios = Factura::selectRaw('socio_id, EXTRACT(YEAR FROM fecha_emision) as anio, EXTRACT(MONTH FROM fecha_emision) as mes, SUM(valor_recibido) as total')
            ->groupBy('socio_id', 'anio', 'mes')
            ->orderBy('anio')->orderBy('mes')
            ->get()
            ->load('socio')
            ->map(fn($f) => [
                'socio' => $f->socio?->nombres ?? 'Desconocido',
                'anio' => (int) $f->anio,
                'mes' => (int) $f->mes,
                'total' => (float) $f->total,
            ])->values();

        $distribucionCierre = (object) [
            'valor_recibido' => $cierresFiltrados->sum('valor_recibido'),
            'total_gastos' => $cierresFiltrados->sum('total_gastos'),
            'saldo' => $cierresFiltrados->sum('saldo'),
            'cuota_administrativa_total' => $cierresFiltrados->sum('cuota_administrativa_total'),
            'retencion_total' => $cierresFiltrados->sum('retencion_total'),
            'total_dinero' => $cierresFiltrados->sum('total_dinero'),
        ];

        $distribucionLabel = $mostrarSuma ? 'Todos los períodos' : $periodoActual;

        return view('dashboard', compact('stats', 'periodoActual', 'kpiCierre', 'prevCierre', 'cierres', 'mesesDisponibles', 'aniosDisponibles', 'topSocios', 'todosTopSocios', 'distribucionCierre', 'distribucionLabel'));
    }

    private function calcularKPIs(?object $kpiCierre, ?object $prevCierre): array
    {
        $facturasActual = $kpiCierre
            ? Factura::whereYear('fecha_emision', $kpiCierre->anio)
                ->whereMonth('fecha_emision', $kpiCierre->mes)->count()
            : 0;

        $facturasAnterior = $prevCierre
            ? Factura::whereYear('fecha_emision', $prevCierre->anio)
                ->whereMonth('fecha_emision', $prevCierre->mes)->count()
            : 0;

        return [
            'valor_recibido' => $kpiCierre?->valor_recibido ?? 0,
            'total_gastos' => $kpiCierre?->total_gastos ?? 0,
            'total_dinero' => $kpiCierre?->total_dinero ?? 0,
            'facturas_emitidas' => $facturasActual,
            'socios_activos' => Socio::where('activo', true)->count(),
            'var_valor_recibido' => $this->reporteService->variacion($kpiCierre?->valor_recibido ?? 0, $prevCierre?->valor_recibido ?? 0),
            'var_total_gastos' => $this->reporteService->variacion($kpiCierre?->total_gastos ?? 0, $prevCierre?->total_gastos ?? 0),
            'var_total_dinero' => $this->reporteService->variacion($kpiCierre?->total_dinero ?? 0, $prevCierre?->total_dinero ?? 0),
            'var_facturas' => $this->reporteService->variacion($facturasActual, $facturasAnterior),
        ];
    }
}

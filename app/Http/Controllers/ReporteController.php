<?php

namespace App\Http\Controllers;

use App\Models\Caja\AporteSocio;
use App\Models\Caja\MovimientoCaja;
use App\Services\ReporteService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function __construct(
        protected ReporteService $reporteService
    ) {}

    public function mensual(Request $request, $anio, $mes)
    {
        $cierres = $this->reporteService->calcularCierres($mes, $anio);
        $cierre = $cierres->first();

        if (!$cierre) {
            return redirect()->route('dashboard')->with('error', 'No hay datos para el período seleccionado.');
        }

        $data = $this->datosReporte($cierre, $anio, $mes);
        $data['titulo'] = 'Reporte Mensual';

        $modo = $request->query('mode', 'preview');

        if ($modo === 'pdf') {
            $data['esPdf'] = true;
            $pdf = Pdf::loadView('reportes.pdf.mensual', $data);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("reporte-mensual-{$anio}-{$mes}.pdf");
        }

        if ($modo === 'print') {
            $data['modo_impresion'] = true;
            return view('reportes.pdf.mensual', $data);
        }

        return view('reportes.pdf.mensual', $data);
    }

    public function anual(Request $request, $anio)
    {
        $cierres = $this->reporteService->calcularCierres(null, $anio);

        if ($cierres->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No hay datos para el año seleccionado.');
        }

        $totales = (object) [
            'valor_recibido' => $cierres->sum('valor_recibido'),
            'total_gastos' => $cierres->sum('total_gastos'),
            'saldo' => $cierres->sum('saldo'),
            'cuota_administrativa_total' => $cierres->sum('cuota_administrativa_total'),
            'retencion_total' => $cierres->sum('retencion_total'),
            'total_dinero' => $cierres->sum('total_dinero'),
        ];

        $dataPorMes = [];
        foreach ($cierres as $c) {
            $dataPorMes[$c->mes] = $this->datosSociosPagos($anio, $c->mes, $c);
        }

        $data = [
            'anio' => $anio,
            'cierres' => $cierres,
            'totales' => $totales,
            'dataPorMes' => $dataPorMes,
            'usuario' => Auth::user()->name ?? 'Sistema',
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'titulo' => "Reporte Anual {$anio}",
        ];

        $modo = $request->query('mode', 'preview');

        if ($modo === 'pdf') {
            $data['esPdf'] = true;
            $pdf = Pdf::loadView('reportes.pdf.anual', $data);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("reporte-anual-{$anio}.pdf");
        }

        if ($modo === 'print') {
            $data['modo_impresion'] = true;
            return view('reportes.pdf.anual', $data);
        }

        return view('reportes.pdf.anual', $data);
    }

    public function situacionFinanciera(Request $request)
    {
        $cierres = $this->reporteService->calcularCierres(null, null);

        if ($cierres->isEmpty()) {
            return redirect()->route('dashboard')->with('error', 'No hay datos disponibles.');
        }

        $totales = (object) [
            'valor_recibido' => $cierres->sum('valor_recibido'),
            'total_gastos' => $cierres->sum('total_gastos'),
            'saldo' => $cierres->sum('saldo'),
            'cuota_administrativa_total' => $cierres->sum('cuota_administrativa_total'),
            'retencion_total' => $cierres->sum('retencion_total'),
            'total_dinero' => $cierres->sum('total_dinero'),
        ];

        $periodo = $cierres->count() > 0
            ? $this->nombreMes($cierres->first()->mes) . " {$cierres->first()->anio} - "
            . $this->nombreMes($cierres->last()->mes) . " {$cierres->last()->anio}"
            : 'Sin datos';

        $dataPorMes = [];
        foreach ($cierres as $c) {
            $dataPorMes[$c->anio . '-' . $c->mes] = $this->datosSociosPagos($c->anio, $c->mes, $c);
        }

        $data = [
            'cierres' => $cierres,
            'totales' => $totales,
            'periodo' => $periodo,
            'dataPorMes' => $dataPorMes,
            'usuario' => Auth::user()->name ?? 'Sistema',
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
            'titulo' => 'Situación Financiera',
        ];

        $modo = $request->query('mode', 'preview');

        if ($modo === 'pdf') {
            $data['esPdf'] = true;
            $pdf = Pdf::loadView('reportes.pdf.situacion-financiera', $data);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download('situacion-financiera.pdf');
        }

        if ($modo === 'print') {
            $data['modo_impresion'] = true;
            return view('reportes.pdf.situacion-financiera', $data);
        }

        return view('reportes.pdf.situacion-financiera', $data);
    }

    private function nombreMes(int $mes): string
    {
        $nombres = ['', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return $nombres[$mes] ?? "Mes {$mes}";
    }

    private function datosSociosPagos($anio, $mes, $cierre): array
    {
        $nombreMes = $this->nombreMes((int) $mes);

        $socios = AporteSocio::with('socio')
            ->where('periodo_anio', $anio)
            ->where('periodo_mes', $mes)
            ->orderBy('socio_id')
            ->get()
            ->map(fn($a) => [
                'nombre' => $a->socio?->nombres ?? 'Desconocido',
                'cuota' => (float) $a->valor_cuota,
                'fecha_pago' => $a->fecha_pago ? $a->fecha_pago->format('d/m/Y') : '-',
                'estado' => $a->estado_pago,
            ]);

        $pagos = MovimientoCaja::with('categoria')
            ->whereYear('fecha', $anio)
            ->whereMonth('fecha', $mes)
            ->where('tipo', 'egreso')
            ->orderBy('categoria_id')
            ->get()
            ->map(fn($m) => [
                'concepto' => $m->descripcion ?: ($m->categoria?->nombre ?? 'Sin concepto'),
                'valor' => (float) $m->valor,
            ]);

        return [
            'nombre_mes' => $nombreMes,
            'cierre' => $cierre,
            'socios' => $socios,
            'pagos' => $pagos,
        ];
    }

    private function datosReporte($cierre, $anio, $mes): array
    {
        return array_merge($this->datosSociosPagos($anio, $mes, $cierre), [
            'anio' => $anio,
            'mes' => $mes,
            'usuario' => Auth::user()->name ?? 'Sistema',
            'fecha_generacion' => now()->format('d/m/Y H:i:s'),
        ]);
    }
}

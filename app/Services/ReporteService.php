<?php

namespace App\Services;

use App\Models\Caja\AporteSocio;
use App\Models\Caja\MovimientoCaja;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Support\Collection;

class ReporteService
{
    public function calcularCierres($mesFiltro = null, $anioFiltro = null): Collection
    {
        $periodos = $this->obtenerPeriodos();

        if ($mesFiltro) {
            $periodos = $periodos->where('mes', (int) $mesFiltro);
        }
        if ($anioFiltro) {
            $periodos = $periodos->where('anio', (int) $anioFiltro);
        }

        $cierres = $periodos->map(function ($p) {
            $ca = AporteSocio::where('periodo_anio', $p['anio'])
                ->where('periodo_mes', $p['mes'])
                ->where('estado_pago', 'pagado')
                ->sum('valor_cuota');

            $tg = MovimientoCaja::whereYear('fecha', $p['anio'])
                ->whereMonth('fecha', $p['mes'])
                ->where('tipo', 'egreso')
                ->sum('valor');

            $ret = FacturaRetencion::whereHas('factura', function ($q) use ($p) {
                $q->whereYear('fecha_emision', $p['anio'])
                  ->whereMonth('fecha_emision', $p['mes']);
            })->sum('valor_retencion');

            $retTuresma = FacturaRetencion::whereHas('factura', function ($q) use ($p) {
                $q->whereYear('fecha_emision', $p['anio'])
                  ->whereMonth('fecha_emision', $p['mes']);
            })->whereHas('tipoRetencion', function ($q) {
                $q->where('slug', 'retencion-turismo-3');
            })->sum('valor_retencion');

            return (object) [
                'mes' => $p['mes'],
                'anio' => $p['anio'],
                'valor_recibido' => $ca + $tg,
                'total_gastos' => $tg,
                'saldo' => $ca,
                'cuota_administrativa_total' => $ca,
                'retencion_total' => $ret,
                '_retTuresma' => $retTuresma,
                'total_dinero' => $ca + $ca + $retTuresma,
            ];
        })->sortBy(['anio', 'mes'])->values();

        foreach ($cierres as $i => $c) {
            if ($i > 0) {
                $prev = $cierres[$i - 1];
                $c->valor_recibido = $prev->total_dinero;
                $c->saldo = $c->valor_recibido - $c->total_gastos;
                $c->total_dinero = $c->saldo + $c->cuota_administrativa_total + $c->_retTuresma;
            } else {
                $c->valor_recibido -= 0.15;
                $c->saldo = $c->valor_recibido - $c->total_gastos;
                $c->total_dinero = $c->saldo + $c->cuota_administrativa_total + $c->_retTuresma;
            }
            unset($c->_retTuresma);
        }

        return $cierres;
    }

    public function obtenerPeriodos(): Collection
    {
        $periodos = collect();

        Factura::selectRaw('EXTRACT(YEAR FROM fecha_emision) as anio, EXTRACT(MONTH FROM fecha_emision) as mes')
            ->distinct()
            ->get()
            ->each(function ($p) use ($periodos) {
                $periodos->push(['anio' => (int) $p->anio, 'mes' => (int) $p->mes]);
            });

        MovimientoCaja::selectRaw('EXTRACT(YEAR FROM fecha) as anio, EXTRACT(MONTH FROM fecha) as mes')
            ->distinct()
            ->get()
            ->each(function ($p) use ($periodos) {
                $periodos->push(['anio' => (int) $p->anio, 'mes' => (int) $p->mes]);
            });

        AporteSocio::select('periodo_anio as anio', 'periodo_mes as mes')
            ->distinct()
            ->get()
            ->each(function ($p) use ($periodos) {
                $periodos->push(['anio' => (int) $p->anio, 'mes' => (int) $p->mes]);
            });

        return $periodos->unique()->sortBy(['anio', 'mes'])->values();
    }

    public function obtenerMesesDisponibles($anio = null): Collection
    {
        $meses = collect();

        Factura::selectRaw('EXTRACT(MONTH FROM fecha_emision) as mes')
            ->distinct()
            ->when($anio, fn($q) => $q->whereYear('fecha_emision', $anio))
            ->get()
            ->each(fn($p) => $meses->push((int) $p->mes));

        MovimientoCaja::selectRaw('EXTRACT(MONTH FROM fecha) as mes')
            ->distinct()
            ->when($anio, fn($q) => $q->whereYear('fecha', $anio))
            ->get()
            ->each(fn($p) => $meses->push((int) $p->mes));

        AporteSocio::select('periodo_mes as mes')
            ->distinct()
            ->when($anio, fn($q) => $q->where('periodo_anio', $anio))
            ->get()
            ->each(fn($p) => $meses->push((int) $p->mes));

        return $meses->unique()->sort()->values();
    }

    public function obtenerAniosDisponibles($mes = null): Collection
    {
        $anios = collect();

        Factura::selectRaw('EXTRACT(YEAR FROM fecha_emision) as anio')
            ->distinct()
            ->when($mes, fn($q) => $q->whereMonth('fecha_emision', $mes))
            ->get()
            ->each(fn($p) => $anios->push((int) $p->anio));

        MovimientoCaja::selectRaw('EXTRACT(YEAR FROM fecha) as anio')
            ->distinct()
            ->when($mes, fn($q) => $q->whereMonth('fecha', $mes))
            ->get()
            ->each(fn($p) => $anios->push((int) $p->anio));

        AporteSocio::select('periodo_anio as anio')
            ->distinct()
            ->when($mes, fn($q) => $q->where('periodo_mes', $mes))
            ->get()
            ->each(fn($p) => $anios->push((int) $p->anio));

        return $anios->unique()->sort()->values();
    }

    public function determinarPeriodoKPI(Collection $cierres, $mesFiltro, $anioFiltro): ?object
    {
        if ($mesFiltro && $anioFiltro) {
            return $cierres->first(fn($c) => $c->mes === (int)$mesFiltro && $c->anio === (int)$anioFiltro);
        }
        if ($anioFiltro) {
            return $cierres->where('anio', (int)$anioFiltro)->last();
        }
        return $cierres->last();
    }

    public function determinarPeriodoAnterior(Collection $cierres, ?object $kpiCierre): ?object
    {
        if (!$kpiCierre) return null;
        $idx = $cierres->search(fn($c) => $c->mes === $kpiCierre->mes && $c->anio === $kpiCierre->anio);
        return $idx !== false && $idx > 0 ? $cierres[$idx - 1] : null;
    }

    public function variacion(float $actual, float $anterior): float
    {
        if ($anterior == 0) return $actual > 0 ? 100 : 0;
        return round((($actual - $anterior) / $anterior) * 100, 1);
    }
}

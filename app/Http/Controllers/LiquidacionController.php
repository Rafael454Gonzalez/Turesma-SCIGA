<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Socio;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaRetencion;
use App\Models\Facturacion\FacturaDistribucion;
use App\Models\Liquidacion\Liquidacion;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class LiquidacionController extends Controller
{
    public function index(Request $request)
    {
        $socioId = $request->query('socio_id');
        $mes = $request->query('periodo_mes');
        $anio = $request->query('periodo_anio');
        $perPage = (int) $request->get('per_page', 10);

        $todas = $this->calcularLiquidaciones($socioId, $mes, $anio);
        $page = $request->get('page', 1);
        $offset = ($page - 1) * $perPage;
        $segmento = $todas->slice($offset, $perPage)->values();

        $liquidaciones = new LengthAwarePaginator(
            $segmento,
            $todas->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        $mesesDisponibles = $this->obtenerMesesDisponibles($anio);
        $aniosDisponibles = $this->obtenerAniosDisponibles($mes);

        return view('liquidaciones.index', compact('liquidaciones', 'socios', 'mesesDisponibles', 'aniosDisponibles'));
    }

    private function calcularLiquidaciones($socioFiltro = null, $mesFiltro = null, $anioFiltro = null): Collection
    {
        $facturas = Factura::with('socio', 'cliente', 'retenciones', 'distribuciones')
            ->when($socioFiltro, fn($q) => $q->where('socio_id', $socioFiltro))
            ->when($mesFiltro, fn($q) => $q->whereMonth('fecha_emision', $mesFiltro))
            ->when($anioFiltro, fn($q) => $q->whereYear('fecha_emision', $anioFiltro))
            ->orderBy('socio_id')->orderBy('fecha_emision')
            ->get();

        $grupos = $facturas->groupBy(fn($f) => $f->socio_id . '-' . $f->fecha_emision->format('Y-m'));

        $socios = Socio::whereIn('id', $facturas->pluck('socio_id')->unique())->get()->keyBy('id');

        return $grupos->map(function ($facturas, $key) use ($socios) {
            $parts = explode('-', $key);
            $socioId = (int) $parts[0];
            $anio = (int) $parts[1];
            $mes = (int) $parts[2];

            $totalFacturado = $facturas->sum('valor_bruto');
            $totalRetenciones = $facturas->sum(fn($f) => $f->retenciones->sum('valor_retencion'));
            $totalDistribuciones = $facturas->sum(fn($f) => $f->distribuciones->sum('valor'));
            $totalNeto = $totalFacturado - $totalRetenciones - $totalDistribuciones;

            $detalles = $facturas->map(fn($f) => (object) [
                'factura' => $f,
                'importe_aplicado' => $f->valor_recibido ?? $f->valor_bruto,
            ]);

            return (object) [
                'id' => null,
                'socio' => $socios->get($socioId),
                'periodo_mes' => $mes,
                'periodo_anio' => $anio,
                'total_facturado' => $totalFacturado,
                'total_retenciones' => $totalRetenciones,
                'total_distribuciones' => $totalDistribuciones,
                'total_neto' => $totalNeto,
                'detalles' => $detalles,
                'estado' => 'calculado',
                'es_calculado' => true,
            ];
        })->sortBy(['periodo_anio', 'periodo_mes', fn($l) => $l->socio->nombres ?? ''])->values();
    }

    private function obtenerMesesDisponibles($anio = null): Collection
    {
        return Factura::selectRaw('EXTRACT(MONTH FROM fecha_emision) as mes')
            ->distinct()
            ->when($anio, fn($q) => $q->whereYear('fecha_emision', $anio))
            ->orderBy('mes')->pluck('mes');
    }

    private function obtenerAniosDisponibles($mes = null): Collection
    {
        return Factura::selectRaw('EXTRACT(YEAR FROM fecha_emision) as anio')
            ->distinct()
            ->when($mes, fn($q) => $q->whereMonth('fecha_emision', $mes))
            ->orderBy('anio')->pluck('anio');
    }

    public function create(Request $request)
    {
        $this->authorizePermission('crear-liquidaciones');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        $socioId = $request->query('socio_id');
        $mes = $request->query('mes');
        $anio = $request->query('anio');

        $facturasDisponibles = collect();
        $yaLiquidadas = collect();

        if ($socioId && $mes && $anio) {
            $liquidacionIds = Liquidacion::where('periodo_mes', $mes)
                ->where('periodo_anio', $anio)
                ->pluck('id');

            $facturasEnLiquidaciones = \App\Models\Liquidacion\LiquidacionDetalle::whereIn('liquidacion_id', $liquidacionIds)
                ->pluck('factura_id');

            $facturasDisponibles = Factura::with('socio', 'cliente')
                ->where('socio_id', $socioId)
                ->whereYear('fecha_emision', $anio)
                ->whereMonth('fecha_emision', $mes)
                ->whereNotIn('id', $facturasEnLiquidaciones)
                ->orderBy('fecha_emision')
                ->get();

            $yaLiquidadas = Factura::with('socio', 'cliente')
                ->whereIn('id', $facturasEnLiquidaciones)
                ->where('socio_id', $socioId)
                ->whereYear('fecha_emision', $anio)
                ->whereMonth('fecha_emision', $mes)
                ->orderBy('fecha_emision')
                ->get();
        }

        $aniosDisponibles = Factura::selectRaw('EXTRACT(YEAR FROM fecha_emision) as anio')
            ->distinct()->orderBy('anio')->pluck('anio');

        return view('liquidaciones.create', compact(
            'socios', 'facturasDisponibles', 'yaLiquidadas', 'aniosDisponibles'
        ));
    }

    public function store(Request $request)
    {
        $this->authorizePermission('crear-liquidaciones');
        $validated = $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'periodo_mes' => 'required|integer|min:1|max:12',
            'periodo_anio' => 'required|integer|min:2000|max:2100',
            'factura_ids' => 'required|array|min:1',
            'factura_ids.*' => 'exists:facturas,id',
        ]);

        $facturas = Factura::with('retenciones', 'distribuciones')
            ->whereIn('id', $validated['factura_ids'])
            ->get();

        $totalFacturado = $facturas->sum('valor_bruto');
        $totalRetenciones = $facturas->sum(fn($f) => $f->retenciones->sum('valor_retencion'));
        $totalDistribuciones = $facturas->sum(fn($f) => $f->distribuciones->sum('valor'));
        $totalNeto = $totalFacturado - $totalRetenciones - $totalDistribuciones;

        $liquidacion = Liquidacion::create([
            'socio_id' => $validated['socio_id'],
            'periodo_mes' => $validated['periodo_mes'],
            'periodo_anio' => $validated['periodo_anio'],
            'total_facturado' => $totalFacturado,
            'total_retenciones' => $totalRetenciones,
            'total_distribuciones' => $totalDistribuciones,
            'total_neto' => $totalNeto,
            'estado' => 'borrador',
            'fecha_generacion' => now(),
            'created_by' => auth()->id(),
        ]);

        foreach ($facturas as $factura) {
            $liquidacion->detalles()->create([
                'factura_id' => $factura->id,
                'importe_aplicado' => $factura->valor_recibido ?? $factura->valor_bruto,
            ]);
        }

        return redirect()->route('liquidaciones.index')
            ->with('success', "Liquidación #{$liquidacion->id} generada exitosamente.");
    }

    public function edit(Liquidacion $liquidacion)
    {
        $this->authorizePermission('aprobar-liquidaciones');
        $liquidacion->load('detalles.factura', 'socio');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();

        return view('liquidaciones.edit', compact('liquidacion', 'socios'));
    }

    public function update(Request $request, Liquidacion $liquidacion)
    {
        $this->authorizePermission('aprobar-liquidaciones');
        $validated = $request->validate([
            'estado' => 'required|in:borrador,emitido,aprobado,cerrado',
        ]);

        $liquidacion->update(['estado' => $validated['estado']]);

        return redirect()->route('liquidaciones.index')
            ->with('success', 'Estado de liquidación actualizado exitosamente.');
    }

    public function destroy(Liquidacion $liquidacion)
    {
        $this->authorizePermission('crear-liquidaciones');
        $liquidacion->detalles()->delete();
        $liquidacion->delete();
        return redirect()->route('liquidaciones.index')
            ->with('success', 'Liquidación eliminada exitosamente.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Catalogos\Cliente;
use App\Models\Catalogos\Socio;
use App\Models\Catalogos\TipoRetencion;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaDistribucion;
use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function index(Request $request)
    {
        $socioId = $request->query('socio_id');
        $clienteId = $request->query('cliente_id');
        $estado = $request->query('estado');
        $anio = $request->query('anio');
        $mes = $request->query('mes');
        $perPage = $request->get('per_page', 5);

        $aniosDisponibles = Factura::selectRaw('EXTRACT(YEAR FROM fecha_emision)::int as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($request->filled('anio')) {
            $anioEfectivo = $anio;
        } else {
            $anioEfectivo = null;
        }

        $mesesDisponibles = Factura::selectRaw('EXTRACT(MONTH FROM fecha_emision)::int as mes')
            ->distinct()
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha_emision', $anioEfectivo))
            ->orderBy('mes')
            ->pluck('mes');

        if ($request->filled('mes')) {
            $mesEfectivo = $mes;
        } else {
            $mesEfectivo = null;
        }

        $query = Factura::with('socio', 'cliente', 'retenciones.tipoRetencion', 'distribuciones.socioDestino');
        if ($socioId) $query->where('socio_id', $socioId);
        if ($clienteId) $query->where('cliente_id', $clienteId);
        if ($estado) $query->where('estado_factura', $estado);
        if ($anioEfectivo) $query->whereYear('fecha_emision', $anioEfectivo);
        if ($mesEfectivo) $query->whereMonth('fecha_emision', $mesEfectivo);
        $facturas = $query->orderBy('fecha_emision')->orderBy('numero_factura')->paginate($perPage)->withQueryString();

        $socioIds = Factura::select('socio_id')->distinct()
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId))
            ->when($estado, fn($q) => $q->where('estado_factura', $estado))
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha_emision', $anioEfectivo))
            ->when($mesEfectivo, fn($q) => $q->whereMonth('fecha_emision', $mesEfectivo))
            ->pluck('socio_id');
        if ($socioId) $socioIds->push((int)$socioId);
        $socios = Socio::whereIn('id', $socioIds->unique())->orderBy('nombres')->get();

        $clienteIds = Factura::select('cliente_id')->distinct()
            ->when($socioId, fn($q) => $q->where('socio_id', $socioId))
            ->when($estado, fn($q) => $q->where('estado_factura', $estado))
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha_emision', $anioEfectivo))
            ->when($mesEfectivo, fn($q) => $q->whereMonth('fecha_emision', $mesEfectivo))
            ->pluck('cliente_id');
        if ($clienteId) $clienteIds->push((int)$clienteId);
        $clientes = Cliente::whereIn('id', $clienteIds->unique())->orderBy('razon_social')->get();

        $estados = Factura::select('estado_factura')->distinct()
            ->when($socioId, fn($q) => $q->where('socio_id', $socioId))
            ->when($clienteId, fn($q) => $q->where('cliente_id', $clienteId))
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha_emision', $anioEfectivo))
            ->when($mesEfectivo, fn($q) => $q->whereMonth('fecha_emision', $mesEfectivo))
            ->pluck('estado_factura');

        return view('facturas.index', compact(
            'facturas', 'socios', 'clientes', 'estados',
            'aniosDisponibles', 'mesesDisponibles', 'anioEfectivo', 'mesEfectivo'
        ));
    }

    public function mesesPorAnio($anio)
    {
        $meses = Factura::selectRaw('EXTRACT(MONTH FROM fecha_emision)::int as mes')
            ->whereYear('fecha_emision', $anio)
            ->distinct()
            ->orderBy('mes')
            ->pluck('mes');

        return response()->json($meses);
    }

    public function create()
    {
        $this->authorizePermission('crear-facturas');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        $clientes = Cliente::where('activo', true)->orderBy('razon_social')->get();
        $tiposRetencion = TipoRetencion::where('activo', true)->orderBy('nombre')->get();

        return view('facturas.create', compact('socios', 'clientes', 'tiposRetencion'));
    }

    public function store(Request $request)
    {
        $this->authorizePermission('crear-facturas');
        $validated = $request->validate([
            'numero_factura' => 'required|string|max:50|unique:facturas,numero_factura',
            'fecha_emision' => 'required|date',
            'socio_id' => 'required|exists:socios,id',
            'cliente_id' => 'required|exists:clientes,id',
            'valor_bruto' => 'required|numeric|min:0',
            'valor_recibido' => 'nullable|numeric|min:0',
            'estado_factura' => 'required|in:pendiente,pagado,anulado',
            'observacion' => 'nullable|string|max:1000',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['valor_recibido'] = $this->calcularValorRecibido(
            $validated['valor_bruto'],
            $request->retenciones ?? [],
            $request->distribuciones ?? []
        );

        $factura = Factura::create($validated);

        if ($request->has('retenciones')) {
            foreach ($request->retenciones as $ret) {
                if (!empty($ret['tipo_retencion_id']) && !empty($ret['valor_retencion'])) {
                    $factura->retenciones()->create([
                        'tipo_retencion_id' => $ret['tipo_retencion_id'],
                        'porcentaje' => $ret['porcentaje'] ?? null,
                        'base_calculo' => $ret['base_calculo'] ?? null,
                        'valor_retencion' => $ret['valor_retencion'],
                        'estado' => 'activo',
                    ]);
                }
            }
        }

        if ($request->has('distribuciones')) {
            $tipos = TipoRetencion::pluck('nombre', 'id');
            foreach ($request->distribuciones as $dist) {
                if (!empty($dist['valor'])) {
                    $tipoRetId = $dist['tipo_retencion_id'] ?? null;
                    $factura->distribuciones()->create([
                        'socio_destino_id' => $factura->socio_id,
                        'tipo_distribucion' => $tipoRetId ? ($tipos[$tipoRetId] ?? 'interna') : 'interna',
                        'valor' => $dist['valor'],
                        'porcentaje' => $dist['porcentaje'] ?? null,
                        'base_calculo' => $dist['base_calculo'] ?? null,
                        'observacion' => $dist['observacion'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Factura creada exitosamente.');
    }

    public function edit(Factura $factura)
    {
        $this->authorizePermission('editar-facturas');
        $factura->load('retenciones', 'distribuciones');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        $clientes = Cliente::where('activo', true)->orderBy('razon_social')->get();
        $tiposRetencion = TipoRetencion::where('activo', true)->orderBy('nombre')->get();

        return view('facturas.edit', compact('factura', 'socios', 'clientes', 'tiposRetencion'));
    }

    public function update(Request $request, Factura $factura)
    {
        $this->authorizePermission('editar-facturas');
        $validated = $request->validate([
            'numero_factura' => 'required|string|max:50|unique:facturas,numero_factura,' . $factura->id,
            'fecha_emision' => 'required|date',
            'socio_id' => 'required|exists:socios,id',
            'cliente_id' => 'required|exists:clientes,id',
            'valor_bruto' => 'required|numeric|min:0',
            'valor_recibido' => 'nullable|numeric|min:0',
            'estado_factura' => 'required|in:pendiente,pagado,anulado',
            'observacion' => 'nullable|string|max:1000',
        ]);

        $validated['updated_by'] = auth()->id();
        $validated['valor_recibido'] = $this->calcularValorRecibido(
            $validated['valor_bruto'],
            $request->retenciones ?? [],
            $request->distribuciones ?? []
        );
        $validated['estado_factura'] = $validated['estado_factura'] ?? $factura->estado_factura;
        $factura->update($validated);

        $factura->retenciones()->delete();
        if ($request->has('retenciones')) {
            foreach ($request->retenciones as $ret) {
                if (!empty($ret['tipo_retencion_id']) && !empty($ret['valor_retencion'])) {
                    $factura->retenciones()->create([
                        'tipo_retencion_id' => $ret['tipo_retencion_id'],
                        'porcentaje' => $ret['porcentaje'] ?? null,
                        'base_calculo' => $ret['base_calculo'] ?? null,
                        'valor_retencion' => $ret['valor_retencion'],
                        'estado' => 'activo',
                    ]);
                }
            }
        }

        $factura->distribuciones()->delete();
        if ($request->has('distribuciones')) {
            $tipos = TipoRetencion::pluck('nombre', 'id');
            foreach ($request->distribuciones as $dist) {
                if (!empty($dist['valor'])) {
                    $tipoRetId = $dist['tipo_retencion_id'] ?? null;
                    $factura->distribuciones()->create([
                        'socio_destino_id' => $factura->socio_id,
                        'tipo_distribucion' => $tipoRetId ? ($tipos[$tipoRetId] ?? 'interna') : 'interna',
                        'valor' => $dist['valor'],
                        'porcentaje' => $dist['porcentaje'] ?? null,
                        'base_calculo' => $dist['base_calculo'] ?? null,
                        'observacion' => $dist['observacion'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('facturas.index')->with('success', 'Factura actualizada exitosamente.');
    }

    private function calcularValorRecibido(float $valorBruto, array $retenciones, array $distribuciones): float
    {
        $total = $valorBruto;
        foreach ($distribuciones as $dist) {
            if (!empty($dist['valor']) && !empty($dist['porcentaje']) && (float) $dist['porcentaje'] > 0) {
                $total -= (float) $dist['valor'];
            }
        }
        return max(0, $total);
    }

    public function destroy(Factura $factura)
    {
        $this->authorizePermission('anular-facturas');
        $factura->retenciones()->delete();
        $factura->distribuciones()->delete();
        $factura->delete();
        return redirect()->route('facturas.index')->with('success', 'Factura eliminada exitosamente.');
    }
}

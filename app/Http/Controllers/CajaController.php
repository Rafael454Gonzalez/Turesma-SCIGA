<?php

namespace App\Http\Controllers;

use App\Models\Caja\AporteSocio;
use App\Models\Caja\MovimientoCaja;
use App\Models\Catalogos\CategoriaMovimiento;
use App\Models\Catalogos\Socio;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Http\Request;

class CajaController extends Controller
{
    // ==================== MOVIMIENTOS ====================

    public function movimientos(Request $request)
    {
        $tipo = $request->query('tipo');
        $catId = $request->query('categoria_id');
        $anio = $request->query('anio');
        $mes = $request->query('mes');
        $perPage = $request->get('per_page', 10);

        $aniosDisponibles = MovimientoCaja::selectRaw('EXTRACT(YEAR FROM fecha)::int as anio')
            ->distinct()
            ->orderBy('anio', 'desc')
            ->pluck('anio');

        if ($request->filled('anio')) {
            $anioEfectivo = $anio;
        } else {
            $anioEfectivo = null;
        }

        $mesesDisponibles = MovimientoCaja::selectRaw('EXTRACT(MONTH FROM fecha)::int as mes')
            ->distinct()
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha', $anioEfectivo))
            ->orderBy('mes')
            ->pluck('mes');

        if ($request->filled('mes')) {
            $mesEfectivo = $mes;
        } else {
            $mesEfectivo = null;
        }

        $query = MovimientoCaja::with('categoria', 'creador');
        if ($tipo) $query->where('tipo', $tipo);
        if ($catId) $query->where('categoria_id', $catId);
        if ($anioEfectivo) $query->whereYear('fecha', $anioEfectivo);
        if ($mesEfectivo) $query->whereMonth('fecha', $mesEfectivo);
        $movimientos = $query->orderBy('fecha')->orderBy('id')->paginate($perPage)->withQueryString();

        $tiposDisponibles = MovimientoCaja::select('tipo')->distinct()
            ->when($catId, fn($q) => $q->where('categoria_id', $catId))
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha', $anioEfectivo))
            ->when($mesEfectivo, fn($q) => $q->whereMonth('fecha', $mesEfectivo))
            ->pluck('tipo');

        $categoriaIds = MovimientoCaja::select('categoria_id')->distinct()
            ->when($tipo, fn($q) => $q->where('tipo', $tipo))
            ->when($anioEfectivo, fn($q) => $q->whereYear('fecha', $anioEfectivo))
            ->when($mesEfectivo, fn($q) => $q->whereMonth('fecha', $mesEfectivo))
            ->pluck('categoria_id');
        if ($catId) $categoriaIds->push((int)$catId);
        $categorias = CategoriaMovimiento::whereIn('id', $categoriaIds->unique())
            ->where('activo', true)->orderBy('nombre')->get();

        return view('caja.movimientos', compact(
            'movimientos', 'categorias', 'tiposDisponibles',
            'aniosDisponibles', 'mesesDisponibles', 'anioEfectivo', 'mesEfectivo'
        ));
    }

    public function movimientosMesesPorAnio($anio)
    {
        $meses = MovimientoCaja::selectRaw('EXTRACT(MONTH FROM fecha)::int as mes')
            ->whereYear('fecha', $anio)
            ->distinct()
            ->orderBy('mes')
            ->pluck('mes');

        return response()->json($meses);
    }

    public function movimientosCreate()
    {
        $this->authorizePermission('crear-movimientos');
        $categorias = CategoriaMovimiento::where('activo', true)->orderBy('nombre')->get();
        return view('caja.movimientos_create', compact('categorias'));
    }

    public function movimientosStore(Request $request)
    {
        $this->authorizePermission('crear-movimientos');
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso,ajuste',
            'categoria_id' => 'required|exists:categorias_movimiento,id',
            'descripcion' => 'required|string|max:500',
            'valor' => 'required|numeric|min:0',
        ]);

        $validated['estado'] = 'activo';
        $validated['created_by'] = auth()->id();

        MovimientoCaja::create($validated);

        return redirect()->route('caja.movimientos.index')
            ->with('success', 'Movimiento creado exitosamente.');
    }

    public function movimientosEdit(MovimientoCaja $movimiento)
    {
        $this->authorizePermission('crear-movimientos');
        $categorias = CategoriaMovimiento::where('activo', true)->orderBy('nombre')->get();
        return view('caja.movimientos_edit', compact('movimiento', 'categorias'));
    }

    public function movimientosUpdate(Request $request, MovimientoCaja $movimiento)
    {
        $this->authorizePermission('crear-movimientos');
        $validated = $request->validate([
            'fecha' => 'required|date',
            'tipo' => 'required|in:ingreso,egreso,ajuste',
            'categoria_id' => 'required|exists:categorias_movimiento,id',
            'descripcion' => 'required|string|max:500',
            'valor' => 'required|numeric|min:0',
        ]);

        $movimiento->update($validated);

        return redirect()->route('caja.movimientos.index')
            ->with('success', 'Movimiento actualizado exitosamente.');
    }

    public function movimientosDestroy(MovimientoCaja $movimiento)
    {
        $this->authorizePermission('crear-movimientos');
        $movimiento->delete();
        return redirect()->route('caja.movimientos.index')
            ->with('success', 'Movimiento eliminado exitosamente.');
    }

    // ==================== CATEGORÍAS (creación rápida) ====================

    public function categoriasStore(Request $request)
    {
        $this->authorizePermission('crear-movimientos');
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categorias_movimiento,nombre',
            'tipo' => 'required|in:ingreso,egreso',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $validated['activo'] = true;

        $categoria = CategoriaMovimiento::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'categoria' => [
                    'id' => $categoria->id,
                    'nombre' => $categoria->nombre,
                ],
            ]);
        }

        return redirect()->back()->with('success', 'Categoría creada exitosamente.');
    }

    // ==================== APORTES ====================

    public function aportes(Request $request)
    {
        $socioId = $request->query('socio_id');
        $mes = $request->query('periodo_mes');
        $anio = $request->query('periodo_anio');
        $estado = $request->query('estado_pago');
        $perPage = $request->get('per_page', 10);

        $query = AporteSocio::with('socio');
        if ($socioId) $query->where('socio_id', $socioId);
        if ($mes) $query->where('periodo_mes', $mes);
        if ($anio) $query->where('periodo_anio', $anio);
        if ($estado) $query->where('estado_pago', $estado);
        $aportes = $query->orderBy('periodo_anio')->orderBy('periodo_mes')->orderBy('socio_id')->paginate($perPage)->withQueryString();

        $socioIds = AporteSocio::select('socio_id')->distinct()
            ->when($mes, fn($q) => $q->where('periodo_mes', $mes))
            ->when($anio, fn($q) => $q->where('periodo_anio', $anio))
            ->when($estado, fn($q) => $q->where('estado_pago', $estado))
            ->pluck('socio_id');
        if ($socioId) $socioIds->push((int)$socioId);
        $socios = Socio::whereIn('id', $socioIds->unique())->orderBy('nombres')->get();

        $mesesDisponibles = AporteSocio::select('periodo_mes')->distinct()
            ->when($socioId, fn($q) => $q->where('socio_id', $socioId))
            ->when($anio, fn($q) => $q->where('periodo_anio', $anio))
            ->when($estado, fn($q) => $q->where('estado_pago', $estado))
            ->orderBy('periodo_mes')->pluck('periodo_mes');

        $aniosDisponibles = AporteSocio::select('periodo_anio')->distinct()
            ->when($socioId, fn($q) => $q->where('socio_id', $socioId))
            ->when($mes, fn($q) => $q->where('periodo_mes', $mes))
            ->when($estado, fn($q) => $q->where('estado_pago', $estado))
            ->orderBy('periodo_anio')->pluck('periodo_anio');

        $estadosDisponibles = AporteSocio::select('estado_pago')->distinct()
            ->when($socioId, fn($q) => $q->where('socio_id', $socioId))
            ->when($mes, fn($q) => $q->where('periodo_mes', $mes))
            ->when($anio, fn($q) => $q->where('periodo_anio', $anio))
            ->pluck('estado_pago');

        return view('caja.aportes', compact('aportes', 'socios', 'mesesDisponibles', 'aniosDisponibles', 'estadosDisponibles'));
    }

    public function aportesCreate()
    {
        $this->authorizePermission('crear-movimientos');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        return view('caja.aportes_create', compact('socios'));
    }

    public function aportesStore(Request $request)
    {
        $this->authorizePermission('crear-movimientos');
        $validated = $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'periodo_mes' => 'required|integer|min:1|max:12',
            'periodo_anio' => 'required|integer|min:2000|max:2100',
            'valor_cuota' => 'required|numeric|min:0',
            'estado_pago' => 'required|in:pendiente,pagado,cancelado,exento',
            'fecha_pago' => 'nullable|date',
            'observacion' => 'nullable|string|max:500',
        ]);

        AporteSocio::create($validated);

        return redirect()->route('caja.aportes.index')
            ->with('success', 'Aporte creado exitosamente.');
    }

    public function aportesEdit(AporteSocio $aporte)
    {
        $this->authorizePermission('crear-movimientos');
        $socios = Socio::where('activo', true)->orderBy('nombres')->get();
        return view('caja.aportes_edit', compact('aporte', 'socios'));
    }

    public function aportesUpdate(Request $request, AporteSocio $aporte)
    {
        $this->authorizePermission('crear-movimientos');
        $validated = $request->validate([
            'socio_id' => 'required|exists:socios,id',
            'periodo_mes' => 'required|integer|min:1|max:12',
            'periodo_anio' => 'required|integer|min:2000|max:2100',
            'valor_cuota' => 'required|numeric|min:0',
            'estado_pago' => 'required|in:pendiente,pagado,cancelado,exento',
            'fecha_pago' => 'nullable|date',
            'observacion' => 'nullable|string|max:500',
        ]);

        $aporte->update($validated);

        return redirect()->route('caja.aportes.index')
            ->with('success', 'Aporte actualizado exitosamente.');
    }

    public function aportesDestroy(AporteSocio $aporte)
    {
        $this->authorizePermission('crear-movimientos');
        $aporte->delete();
        return redirect()->route('caja.aportes.index')
            ->with('success', 'Aporte eliminado exitosamente.');
    }

}

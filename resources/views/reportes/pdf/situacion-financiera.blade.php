<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }}</title>
    <style>
        @page { margin: 12mm 8mm 18mm 8mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 14px; color: #1e293b; line-height: 1.6; }
        .header { text-align: center; border-bottom: 2px solid #E31E24; padding-bottom: 12px; margin-bottom: 18px; }
        .header .brand { font-size: 28px; font-weight: 900; font-style: italic; color: #E31E24; }
        .header .subtitle { font-size: 12px; color: #64748b; text-transform: uppercase; letter-spacing: 2px; }
        .header .periodo { font-size: 16px; font-weight: 700; color: #0b0f1a; margin-top: 4px; }
        .info-bar { display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 13px; page-break-inside: avoid; }
        th { background: #0b0f1a; color: #fff; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 9px 10px; text-align: right; }
        th:first-child { text-align: left; }
        td { padding: 8px 10px; text-align: right; border-bottom: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; }
        td:first-child { text-align: left; font-weight: 700; color: #0b0f1a; }
        tr.total td { background: #fef2f2; font-weight: 800; font-size: 14px; border-top: 2px solid #E31E24; border-bottom: 2px solid #E31E24; }
        .footer { position: fixed; bottom: 8mm; left: 12mm; right: 12mm; text-align: center; font-size: 10px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 4px; }
        .page-number:before { content: "Página " counter(page); }
        .resumen-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .resumen-table td { padding: 6px 8px; text-align: center; border-radius: 4px; font-weight: 900; }
        .resumen-table .label { font-size: 6px; text-transform: uppercase; letter-spacing: 0.3px; font-weight: 700; display: block; }
        .resumen-table .value { font-size: 12px; font-weight: 900; margin-top: 1px; display: block; }
        .bg-green { background: #f0fdf4; color: #16a34a; }
        .bg-red { background: #fef2f2; color: #dc2626; }
        .bg-dark { background: #f1f5f9; color: #0b0f1a; }
        .bg-blue { background: #eff6ff; color: #2563eb; }
        .bg-amber { background: #fffbeb; color: #d97706; }
    </style>
</head>
<body>
    @if (!($esPdf ?? false))
    <div style="margin-bottom: 12px;">
        <a href="{{ route('dashboard') }}"
           style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #f1f5f9; color: #475569; text-decoration: none; border-radius: 10px; font-size: 11px; font-weight: 700; border: 1px solid #e2e8f0;">
            ← Volver al Dashboard
        </a>
    </div>
    @endif

    @php
        $logoPath = public_path('storage/logo.png');
        $logoSrc = null;
        if (file_exists($logoPath)) {
            $type = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($logoPath));
        }
    @endphp
    <div class="header">
        @if ($logoSrc)
            <img src="{{ $logoSrc }}" alt="Logo" style="height: 45px; width: auto; margin-bottom: 4px;">
        @else
            <div class="brand">Turesma</div>
        @endif
        <div class="subtitle">Sistema de Control de Ingresos y Gastos Administrativos</div>
        <div class="periodo">Situación Financiera</div>
    </div>

    <div class="info-bar">
        <span>Generado por: {{ $usuario }}</span>
        <span>Período: {{ $periodo }}</span>
        <span>{{ $fecha_generacion }}</span>
    </div>

    <table class="resumen-table">
        <tr>
            <td class="bg-green"><span class="label">Total Valor Recibido</span><span class="value">${{ number_format($totales->valor_recibido, 2) }}</span></td>
            <td class="bg-red"><span class="label">Total Gastos</span><span class="value">${{ number_format($totales->total_gastos, 2) }}</span></td>
            <td class="bg-dark"><span class="label">Total Dinero</span><span class="value">${{ number_format($totales->total_dinero, 2) }}</span></td>
            <td class="bg-blue"><span class="label">Cuotas Admin.</span><span class="value">${{ number_format($totales->cuota_administrativa_total, 2) }}</span></td>
            <td class="bg-amber"><span class="label">Retenciones</span><span class="value">${{ number_format($totales->retencion_total, 2) }}</span></td>
        </tr>
    </table>

    <div style="page-break-inside: avoid;">
    <table>
        <thead>
            <tr>
                <th>Mes</th>
                <th>Valor Recibido</th>
                <th>Total Gastos</th>
                <th>Saldo</th>
                <th>Cuota Admin.</th>
                <th>Retención 3%</th>
                <th>Total Dinero</th>
            </tr>
        </thead>
        <tbody>
            @php $mesNombres = ['', 'Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre']; @endphp
            @foreach ($cierres as $c)
            <tr>
                <td>{{ $mesNombres[$c->mes] ?? $c->mes }} {{ $c->anio }}</td>
                <td>${{ number_format($c->valor_recibido, 2) }}</td>
                <td>${{ number_format($c->total_gastos, 2) }}</td>
                <td>${{ number_format($c->saldo, 2) }}</td>
                <td>${{ number_format($c->cuota_administrativa_total, 2) }}</td>
                <td>${{ number_format($c->retencion_total, 2) }}</td>
                <td>${{ number_format($c->total_dinero, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td>TOTAL GENERAL</td>
                <td>${{ number_format($totales->valor_recibido, 2) }}</td>
                <td>${{ number_format($totales->total_gastos, 2) }}</td>
                <td>${{ number_format($totales->saldo, 2) }}</td>
                <td>${{ number_format($totales->cuota_administrativa_total, 2) }}</td>
                <td>${{ number_format($totales->retencion_total, 2) }}</td>
                <td>${{ number_format($totales->total_dinero, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    </div>

    @foreach ($cierres as $c)
    @php $key = $c->anio . '-' . $c->mes; $m = $dataPorMes[$key] ?? null; @endphp
    @if ($m)
    <div style="page-break-before: always;"></div>

    <div style="page-break-inside: avoid;">
        <h3 style="font-size: 11px; font-weight: 900; color: #0b0f1a; margin: 14px 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">Socios — {{ $m['nombre_mes'] }} {{ $c->anio }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Socio</th>
                    <th style="width: 25%;">Cuota</th>
                    <th style="width: 25%;">Fecha de Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($m['socios'] as $s)
                <tr>
                    <td>{{ $s['nombre'] }}</td>
                    <td>{{ $s['cuota'] > 0 ? '$' . number_format($s['cuota'], 2) : ($s['estado'] === 'exento' ? 'Exento' : 'Cancelado') }}</td>
                    <td>{{ $s['fecha_pago'] }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align: center; color: #94a3b8;">Sin registros</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="page-break-inside: avoid;">
        <h3 style="font-size: 11px; font-weight: 900; color: #0b0f1a; margin: 14px 0 8px 0; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px;">Pagos — {{ $m['nombre_mes'] }} {{ $c->anio }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 70%;">Concepto</th>
                    <th style="width: 30%;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($m['pagos'] as $p)
                <tr>
                    <td>{{ $p['concepto'] }}</td>
                    <td>${{ number_format($p['valor'], 2) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="2" style="text-align: center; color: #94a3b8;">Sin registros</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
    @endforeach

    <div class="footer">
        <span class="page-number"></span> — {{ $titulo }}
    </div>
</body>
</html>

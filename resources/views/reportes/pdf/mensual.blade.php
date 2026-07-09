<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $titulo }} - {{ $nombre_mes }} {{ $anio }}</title>
    <style>
        @page { margin: 20mm 15mm 25mm 15mm; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; }
        .header { text-align: center; border-bottom: 2px solid #E31E24; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 22px; font-weight: 900; margin: 0; color: #0b0f1a; letter-spacing: -0.5px; }
        .header .brand { font-size: 28px; font-weight: 900; font-style: italic; color: #E31E24; }
        .header .subtitle { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 2px; margin-top: 2px; }
        .header .periodo { font-size: 13px; font-weight: 700; color: #0b0f1a; margin-top: 4px; }
        .info-bar { display: flex; justify-content: space-between; font-size: 9px; color: #94a3b8; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 0.5px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #0b0f1a; color: #fff; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; padding: 8px 10px; text-align: right; }
        th:first-child { text-align: left; }
        td { padding: 7px 10px; text-align: right; border-bottom: 1px solid #e2e8f0; font-size: 11px; font-weight: 600; }
        td:first-child { text-align: left; font-weight: 400; color: #475569; }
        tr.total td { background: #fef2f2; font-weight: 800; font-size: 12px; border-top: 2px solid #E31E24; border-bottom: 2px solid #E31E24; }
        .footer { position: fixed; bottom: 10mm; left: 15mm; right: 15mm; text-align: center; font-size: 8px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
        .page-number:before { content: "Página " counter(page); }
        .grafico { text-align: center; margin: 20px 0; }
        .grafico img { max-width: 100%; height: auto; }
        .badge { display: inline-block; background: #f1f5f9; color: #475569; font-size: 8px; font-weight: 700; padding: 2px 8px; border-radius: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        .resumen-table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        .resumen-table td { padding: 10px; text-align: center; border-radius: 6px; font-weight: 900; }
        .resumen-table .label { font-size: 7px; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 700; display: block; }
        .resumen-table .value { font-size: 14px; font-weight: 900; margin-top: 2px; display: block; }
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
            <img src="{{ $logoSrc }}" alt="Logo" style="height: 50px; width: auto; margin-bottom: 6px;">
        @else
            <div class="brand">Turesma</div>
        @endif
        <div class="subtitle">Sistema de Control de Ingresos y Gastos Administrativos</div>
        <div class="periodo">{{ $nombre_mes }} {{ $anio }}</div>
    </div>

    <div class="info-bar">
        <span>Generado por: {{ $usuario }}</span>
        <span>{{ $fecha_generacion }}</span>
    </div>

    <table class="resumen-table">
        <tr>
            <td class="bg-green"><span class="label">Valor Recibido</span><span class="value">${{ number_format($cierre->valor_recibido, 2) }}</span></td>
            <td class="bg-red"><span class="label">Total Gastos</span><span class="value">${{ number_format($cierre->total_gastos, 2) }}</span></td>
            <td class="bg-dark"><span class="label">Total Dinero</span><span class="value">${{ number_format($cierre->total_dinero, 2) }}</span></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Concepto</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Valor Recibido</td>
                <td>${{ number_format($cierre->valor_recibido, 2) }}</td>
            </tr>
            <tr>
                <td>Total Gastos</td>
                <td>${{ number_format($cierre->total_gastos, 2) }}</td>
            </tr>
            <tr>
                <td>Saldo</td>
                <td>${{ number_format($cierre->saldo, 2) }}</td>
            </tr>
            <tr>
                <td>Cuota Administrativa</td>
                <td>${{ number_format($cierre->cuota_administrativa_total, 2) }}</td>
            </tr>
            <tr>
                <td>Retención 3%</td>
                <td>${{ number_format($cierre->retencion_total, 2) }}</td>
            </tr>
            <tr class="total">
                <td>Total de Dinero</td>
                <td>${{ number_format($cierre->total_dinero, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="page-break-inside: avoid;">
        <h3 style="font-size: 12px; font-weight: 900; color: #0b0f1a; margin: 16px 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">Socios — {{ $nombre_mes }} {{ $anio }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Socio</th>
                    <th style="width: 25%;">Cuota</th>
                    <th style="width: 25%;">Fecha de Pago</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($socios as $s)
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
        <h3 style="font-size: 12px; font-weight: 900; color: #0b0f1a; margin: 16px 0 10px 0; text-transform: uppercase; letter-spacing: 0.5px;">Pagos {{ $nombre_mes }} {{ $anio }}</h3>
        <table>
            <thead>
                <tr>
                    <th style="width: 70%;">Concepto</th>
                    <th style="width: 30%;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pagos as $p)
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

    <div class="footer">
        <span class="page-number"></span> — {{ $titulo }} — {{ $nombre_mes }} {{ $anio }}
    </div>

    @if (($modo_impresion ?? false))
    <script>
        window.print();
    </script>
    @endif
</body>
</html>

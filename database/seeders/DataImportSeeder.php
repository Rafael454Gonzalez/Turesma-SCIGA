<?php

namespace Database\Seeders;

use App\Models\Caja\AporteSocio;
use App\Models\Caja\MovimientoCaja;
use App\Models\Catalogos\CategoriaMovimiento;
use App\Models\Catalogos\Cliente;
use App\Models\Catalogos\Socio;
use App\Models\Catalogos\TipoRetencion;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaDistribucion;
use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Database\Seeder;

class DataImportSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Iniciando importacion de datos desde Excel...');
        $this->command->warn('NOTA: Los datos se importan desde la estructura del Excel.');
        $this->command->warn('Las fechas estan en formato serial de Excel y se convierten automaticamente.');
        echo PHP_EOL;

        // ============================================================
        // 1. TIPOS DE RETENCION
        // ============================================================
        $this->crearTiposRetencion();

        // ============================================================
        // 2. SOCIOS (de ambos archivos)
        // ============================================================
        $this->crearSocios();

        // ============================================================
        // 3. CLIENTES (de la liquidacion)
        // ============================================================
        $this->crearClientes();

        // ============================================================
        // 4. FACTURAS, RETENCIONES Y DISTRIBUCIONES (ENERO)
        // ============================================================
        $this->importarLiquidacionEnero();

        // ============================================================
        // 5. CATEGORIAS DE MOVIMIENTO (del reporte de caja)
        // ============================================================
        $this->crearCategoriasMovimiento();

        // ============================================================
        // 6. APORTES DE SOCIOS + MOVIMIENTOS + CIERRES (ENERO)
        // ============================================================
        $this->importarReporteEnero();

        // ============================================================
        // 7. APORTES + MOVIMIENTOS + CIERRES (FEBRERO)
        // ============================================================
        $this->importarReporteFebrero();

        // ============================================================
        // 8. FACTURAS ABRIL
        // ============================================================
        $this->importarLiquidacionAbril();

        // ============================================================
        // 9. FACTURAS MAYO
        // ============================================================
        $this->importarLiquidacionMayo();

        // ============================================================
        // 10. FACTURAS JUNIO
        // ============================================================
        $this->importarLiquidacionJunio();

        // ============================================================
        // 11. REPORTE ABRIL
        // ============================================================
        $this->importarReporteAbril();

        // ============================================================
        // 12. REPORTE MAYO
        // ============================================================
        $this->importarReporteMayo();

        // ============================================================
        // 13. REPORTE JUNIO
        // ============================================================
        $this->importarReporteJunio();

        $this->command->info('');
        $this->command->info('========== IMPORTACION COMPLETADA ==========');
        $this->command->info('Socios: ' . Socio::count());
        $this->command->info('Clientes: ' . Cliente::count());
        $this->command->info('Facturas: ' . Factura::count());
        $this->command->info('Retenciones: ' . FacturaRetencion::count());
        $this->command->info('Distribuciones: ' . FacturaDistribucion::count());
        $this->command->info('Categorias: ' . CategoriaMovimiento::count());
        $this->command->info('Movimientos Caja: ' . MovimientoCaja::count());
        $this->command->info('Aportes Socios: ' . AporteSocio::count());
    }

    // ---- CONVERTIR FECHA SERIAL DE EXCEL A Y-m-d ----
    private function excelSerialToDate($serial)
    {
        if (empty($serial) || $serial === 0) return null;
        $unix = ($serial - 25569) * 86400;
        return date('Y-m-d', $unix);
    }

    // ---- 1. TIPOS DE RETENCION ----
    private function crearTiposRetencion()
    {
        $this->command->info('Creando tipos de retencion...');

        TipoRetencion::firstOrCreate(
            ['slug' => 'retencion-1'],
            [
                'nombre' => 'Retencion 1%',
                'porcentaje' => 1.00,
                'descripcion' => 'Retencion del 1% sobre valor de factura',
                'activo' => true,
            ]
        );

        TipoRetencion::firstOrCreate(
            ['slug' => 'retencion-turismo-3'],
            [
                'nombre' => 'Retencion Turismo 3%',
                'porcentaje' => 3.00,
                'descripcion' => 'Retencion de turismo del 3% sobre valor recibido',
                'activo' => true,
            ]
        );
    }

    // ---- 2. SOCIOS ----
    private function crearSocios()
    {
        $this->command->info('Creando socios...');

        $socios = [
            // nombre, identificacion, cuota_mensual_base, tipo_socio
            ['Nelson Jimenez', 'NELSON001', 75, 'socio'],
            ['Freddy Delgado', 'FREDDY001', 150, 'socio'],
            ['Gustavo Bunay', 'GUSTAVO001', 75, 'socio'],
            ["Letty's Williams", 'LETTY001', 75, 'socio'],
            ['Eliana Mero', 'ELIANA001', 0, 'colaborador'],
            ['Rogelio Molina', 'ROGELIO001', 75, 'socio'],
        ];

        foreach ($socios as $s) {
            Socio::firstOrCreate(
                ['nombres' => $s[0]],
                [
                    'identificacion' => $s[1],
                    'cuota_mensual_base' => $s[2],
                    'tipo_socio' => $s[3],
                    'activo' => true,
                    'fecha_registro' => '2026-01-01',
                ]
            );
        }
    }

    // ---- 3. CLIENTES ----
    private function crearClientes()
    {
        $this->command->info('Creando clientes...');

        $clientes = [
            ['ECUANATICA S.A', 'ECUA001'],
            ['PACIFICTUNA S.A', 'PAC001'],
            ['TRABEL AGENCY MEETECUADOR', 'TRABEL001'],
            ['MOKACHINOEXPRES S.A', 'MOKA001'],
            ['GUAYATUNA S.A', 'GUAYA001'],
            // Abril
            ['ALEX PAREDES', 'ALEX001'],
            ['UNIVERSIDAD UTE', 'UTE001'],
            ['NARWELL TOURS', 'NARW001'],
            ['ANDESPORTS S.A.', 'ANDE001'],
            ['VANSERTRANS S.A', 'VANS001'],
            // Mayo
            ['HOTEL ORO VERDE MANDA', 'OROV001'],
            ['TRAVEL AGENCY MEETECUADOR', 'TRAV001'],
            ['CARRILLO CHICO REGULO', 'CARR001'],
            // Junio
            ['SWEADEN S.A', 'SWEA001'],
            ['GRUPO MANCHENO', 'GRUP001'],
            ['TRANSEPICENTRO S.A', 'TRANS001'],
            ['CHIGUANO YANGUISELA MARCO', 'CHIG001'],
        ];

        foreach ($clientes as $c) {
            Cliente::firstOrCreate(
                ['razon_social' => $c[0]],
                [
                    'ruc' => $c[1],
                    'activo' => true,
                ]
            );
        }
    }

    // ---- 4. FACTURAS - ENERO (LIQUIDACION) ----
    private function importarLiquidacionEnero()
    {
        $this->command->info('Importando facturas de Enero (Liquidacion)...');

        $socios = Socio::pluck('id', 'nombres');
        $clientes = Cliente::pluck('id', 'razon_social');
        $retencion3 = TipoRetencion::where('slug', 'retencion-turismo-3')->first()->id;

        // Datos extraidos del Excel: [fecha, nro_factura, socio, cliente, valor_bruto,
        //   ret_1%, v_recibido, ret_3%, v_neto, estado, no_retiene, dist_freddy, dist_nelson]
        $facturas = [
            [46034, 180, 'Nelson Jimenez', 'ECUANATICA S.A', 165, 1.65, 163.35, 4.90, 158.45, 'PAGADO', false, 7.43, 4.90],
            [46035, 181, 'Freddy Delgado', 'PACIFICTUNA S.A', 250, 2.50, 247.50, 7.42, 240.07, null, false, 16.34, 11.88],
            [46035, 182, 'Freddy Delgado', 'TRABEL AGENCY MEETECUADOR', 550, 5.50, 544.50, 16.34, 528.16, null, false, 9.00, 14.12],
            [46038, 183, 'Freddy Delgado', 'MOKACHINOEXPRES S.A', 300, null, 300.00, 9.00, 291.00, null, true, 2.25, 4.60],
            [46038, 184, 'Nelson Jimenez', 'ECUANATICA S.A', 400, 4.00, 396.00, 11.88, 384.12, 'PAGADO', false, 0, 13.07],
            [46039, 185, 'Freddy Delgado', 'MOKACHINOEXPRES S.A', 75, null, 75.00, 2.25, 72.75, null, true, 35.02, 48.57],
            [46041, 186, 'Nelson Jimenez', 'GUAYATUNA S.A', 475.50, 4.76, 470.75, 14.12, 456.62, 'PAGADO', false, 0, 0],
            [46041, 187, 'Nelson Jimenez', 'GUAYATUNA S.A', 155, 1.55, 153.45, 4.60, 148.85, 'PAGADO', false, 83.59, 0],
            [46042, 188, 'Nelson Jimenez', 'ECUANATICA S.A', 440, 4.40, 435.60, 13.07, 422.53, 'PAGADO', false, 0, 0],
        ];

        $adminId = 1; // el admin que creo

        foreach ($facturas as $f) {
            $nroFactura = $f[1];
            if (Factura::where('numero_factura', (string)$nroFactura)->exists()) {
                $this->command->warn("  Factura #$nroFactura ya existe, saltando...");
                continue;
            }

            $fecha = $this->excelSerialToDate($f[0]);
            $socioId = $socios[$f[2]] ?? null;
            $clienteId = $clientes[$f[3]] ?? null;
            $valorBruto = $f[4];
            $ret1 = $f[5]; // puede ser null
            $valorRecibido = $f[6];
            $ret3 = $f[7];
            $estado = $f[9] ?? 'pendiente';
            $noRetiene = $f[10];
            $distFreddy = $f[11] ?? 0;
            $distNelson = $f[12] ?? 0;

            // Crear factura
            $factura = Factura::create([
                'numero_factura' => (string) $f[1],
                'fecha_emision' => $fecha,
                'socio_id' => $socioId,
                'cliente_id' => $clienteId,
                'valor_bruto' => $valorBruto,
                'valor_recibido' => $valorRecibido,
                'estado_factura' => $estado,
                'created_by' => $adminId,
            ]);

            // Retencion Turismo 3%
            FacturaRetencion::create([
                'factura_id' => $factura->id,
                'tipo_retencion_id' => $retencion3,
                'porcentaje' => 3.00,
                'base_calculo' => $valorRecibido,
                'valor_retencion' => $ret3,
            ]);

            // Distribucion 1% (se movio de retencion a distribucion)
            if ($ret1 !== null && $ret1 > 0) {
                FacturaDistribucion::create([
                    'factura_id' => $factura->id,
                    'socio_destino_id' => $socioId,
                    'tipo_distribucion' => 'interna',
                    'valor' => round($ret1, 2),
                    'porcentaje' => 1.00,
                    'observacion' => 'Distribucion 1%',
                ]);
            }

            // Distribuciones internas entre socios
            if ($distFreddy > 0) {
                FacturaDistribucion::create([
                    'factura_id' => $factura->id,
                    'socio_destino_id' => $socios['Freddy Delgado'],
                    'tipo_distribucion' => 'interna',
                    'valor' => round($distFreddy, 2),
                    'observacion' => 'Distribucion a Freddy',
                ]);
            }
            if ($distNelson > 0) {
                FacturaDistribucion::create([
                    'factura_id' => $factura->id,
                    'socio_destino_id' => $socios['Nelson Jimenez'],
                    'tipo_distribucion' => 'interna',
                    'valor' => round($distNelson, 2),
                    'observacion' => 'Distribucion a Nelson',
                ]);
            }
        }
    }

    // ---- 5. CATEGORIAS DE MOVIMIENTO ----
    private function crearCategoriasMovimiento()
    {
        $this->command->info('Creando categorias de movimiento...');

        $categorias = [
            ['Pago a contador', 'egreso'],
            ['Movilizacion', 'egreso'],
            ['Servicios bancarios', 'egreso'],
            ['Emision de facturas', 'egreso'],
            ['Diferencia al contador', 'egreso'],
            ['Chequera', 'egreso'],
            ['IESS', 'egreso'],
            ['Protesta de cheques', 'egreso'],
            ['Renovacion de factura', 'egreso'],
            ['Aporte agencia de transito', 'egreso'],
            ['Cuota administrativa', 'ingreso'],
            ['Valor recibido facturas', 'ingreso'],
            ['Retencion 3%', 'ingreso'],
        ];

        foreach ($categorias as $c) {
            CategoriaMovimiento::firstOrCreate(
                ['nombre' => $c[0]],
                [
                    'tipo' => $c[1],
                    'activo' => true,
                ]
            );
        }
    }

    // ---- 6. REPORTE ENERO ----
    private function importarReporteEnero()
    {
        $this->command->info('Importando Reporte de Caja - Enero...');

        $socios = Socio::pluck('id', 'nombres');
        $categorias = CategoriaMovimiento::pluck('id', 'nombre');
        $adminId = 1;

        // --- APORTES DE SOCIOS (ENERO) ---
        $aportesEnero = [
            ['Nelson Jimenez', 75, 46052, 'pagado'],
            ['Gustavo Bunay', 75, 46090, 'pagado'],
            ["Letty's Williams", null, 46041, 'cancelado'],
            ['Freddy Delgado', 150, 46055, 'pagado'],
            ['Eliana Mero', 0, null, 'exento'],
            ['Rogelio Molina', 75, 46054, 'pagado'],
        ];

        foreach ($aportesEnero as $a) {
            $socioId = $socios[$a[0]] ?? null;
            $existe = AporteSocio::where('socio_id', $socioId)
                ->where('periodo_mes', 1)->where('periodo_anio', 2026)->exists();
            if (!$existe) {
                AporteSocio::create([
                    'socio_id' => $socioId,
                    'periodo_mes' => 1,
                    'periodo_anio' => 2026,
                    'valor_cuota' => $a[1] ?? 0,
                    'estado_pago' => $a[3],
                    'fecha_pago' => $a[2] ? $this->excelSerialToDate($a[2]) : null,
                ]);
            }
        }

        // --- INGRESOS / MOVIMIENTOS (ENERO) ---
        $catValorRecibido = $categorias['Valor recibido facturas'];
        $existeVR = MovimientoCaja::where('descripcion', 'VALOR RECIBIDO FACTURAS - ENERO')->exists();
        if (!$existeVR) {
            MovimientoCaja::create([
                'fecha' => '2026-01-31',
                'tipo' => 'ingreso',
                'categoria_id' => $catValorRecibido,
                'descripcion' => 'VALOR RECIBIDO FACTURAS - ENERO',
                'valor' => 1220.85,
                'created_by' => $adminId,
            ]);
        }

        // --- GASTOS / MOVIMIENTOS (ENERO) ---
        $gastosEnero = [
            ['PAGO DE IESS', 'IESS', 210.33],
            ['PAGO DE DIFERENCIA AL CONTADOR', 'Diferencia al contador', 222.19],
            ['PAGO DE PROTESTA CHEQUES', 'Protesta de cheques', 192.04],
            ['EMISION DE FACTURAS', 'Emision de facturas', 35.00],
            ['PAGO A CONTADOR-ENERO', 'Pago a contador', 165.00],
            ['MOVILIZACION', 'Movilizacion', 20.00],
            ['SERVICIOS BANCARIOS', 'Servicios bancarios', 3.62],
        ];

        foreach ($gastosEnero as $g) {
            $catId = $categorias[$g[1]] ?? null;
            $existe = MovimientoCaja::where('descripcion', $g[0])
                ->where('tipo', 'egreso')->whereMonth('fecha', 1)->exists();
            if (!$existe) {
                MovimientoCaja::create([
                    'fecha' => '2026-01-31',
                    'tipo' => 'egreso',
                    'categoria_id' => $catId,
                    'descripcion' => $g[0],
                    'valor' => $g[2],
                    'created_by' => $adminId,
                ]);
            }
        }

    }

    // ---- 7. REPORTE FEBRERO ----
    private function importarReporteFebrero()
    {
        $this->command->info('Importando Reporte de Caja - Febrero...');

        $socios = Socio::pluck('id', 'nombres');
        $categorias = CategoriaMovimiento::pluck('id', 'nombre');
        $adminId = 1;

        // --- APORTES DE SOCIOS (FEBRERO) ---
        $aportesFebrero = [
            ['Nelson Jimenez', 75, 46059, 'pagado'],
            ['Gustavo Bunay', 75, 46090, 'pagado'],
            ["Letty's Williams", 75, 46059, 'pagado'],
            ['Freddy Delgado', 150, 46068, 'pagado'],
            ['Eliana Mero', 0, null, 'exento'],
            ['Rogelio Molina', 75, 46090, 'pagado'],
        ];

        foreach ($aportesFebrero as $a) {
            $socioId = $socios[$a[0]] ?? null;
            $existe = AporteSocio::where('socio_id', $socioId)
                ->where('periodo_mes', 2)->where('periodo_anio', 2026)->exists();
            if (!$existe) {
                AporteSocio::create([
                    'socio_id' => $socioId,
                    'periodo_mes' => 2,
                    'periodo_anio' => 2026,
                    'valor_cuota' => $a[1] ?? 0,
                    'estado_pago' => $a[3],
                    'fecha_pago' => $a[2] ? $this->excelSerialToDate($a[2]) : null,
                ]);
            }
        }

        // --- INGRESOS / MOVIMIENTOS (FEBRERO) ---
        $catValorRecibido = $categorias['Valor recibido facturas'];
        $existeVR = MovimientoCaja::where('descripcion', 'VALOR RECIBIDO FACTURAS - FEBRERO')->exists();
        if (!$existeVR) {
            MovimientoCaja::create([
                'fecha' => '2026-02-28',
                'tipo' => 'ingreso',
                'categoria_id' => $catValorRecibido,
                'descripcion' => 'VALOR RECIBIDO FACTURAS - FEBRERO',
                'valor' => 831.25,
                'created_by' => $adminId,
            ]);
        }

        // --- GASTOS / MOVIMIENTOS (FEBRERO) ---
        $gastosFebrero = [
            ['Renovacion de factura', 'Renovacion de factura', 96.03],
            ['Aporte de la Agencia de transito', 'Aporte agencia de transito', 200.00],
            ['Chequera', 'Chequera', 6.20],
            ['Movilizacion', 'Movilizacion', 20.00],
            ['Pago de emision de facturas', 'Emision de facturas', 35.00],
            ['Pago al contador-Febrero', 'Pago a contador', 165.00],
            ['Servicios Bancarios', 'Servicios bancarios', 0.82],
        ];

        foreach ($gastosFebrero as $g) {
            $catId = $categorias[$g[1]] ?? null;
            $existe = MovimientoCaja::where('descripcion', $g[0])
                ->where('tipo', 'egreso')->whereMonth('fecha', 2)->exists();
            if (!$existe) {
                MovimientoCaja::create([
                    'fecha' => '2026-02-28',
                    'tipo' => 'egreso',
                    'categoria_id' => $catId,
                    'descripcion' => $g[0],
                    'valor' => $g[2],
                    'created_by' => $adminId,
                ]);
            }
        }

    }

    // ---- HELPER: Importar facturas generico ----
    private function importarFacturas(string $label, string $fechaDefault, array $facturas): void
    {
        $this->command->info("Importando facturas de $label...");

        $socios = Socio::pluck('id', 'nombres');
        $clientes = Cliente::pluck('id', 'razon_social');
        $retencion3 = TipoRetencion::where('slug', 'retencion-turismo-3')->first()->id;
        $adminId = 1;

        // Normalizar nombres de socios (Excel puede tener LETTY WILLIAMS sin apostrofe)
        $socioMap = [
            'LETTY WILLIAMS' => "Letty's Williams",
            'LETTY\'S WILLIAMS' => "Letty's Williams",
            'FREDDY DELGADO' => 'Freddy Delgado',
            'NELSON JIMENEZ' => 'Nelson Jimenez',
            'GUSTAVO BUNAY' => 'Gustavo Bunay',
            'ELIANA MERO' => 'Eliana Mero',
            'ROGELIO MOLINA' => 'Rogelio Molina',
        ];

        foreach ($facturas as $f) {
            $nroFactura = $f[0];
            $socioNombre = $f[1];
            $clienteNombre = $f[2];
            $valorBruto = $f[3];
            $ret1 = $f[4];
            $valorRecibido = $f[5];
            $ret3 = $f[6];
            $estado = $f[7];

            // Saltar si ya existe
            if (Factura::where('numero_factura', (string)$nroFactura)->exists()) {
                $this->command->warn("  Factura #$nroFactura ya existe, saltando...");
                continue;
            }

            // Saltar facturas anuladas (sin cliente real)
            if (strtoupper($clienteNombre) === 'ANULADA') {
                $this->command->warn("  Factura #$nroFactura es ANULADA, saltando...");
                continue;
            }

            // Normalizar nombre socio
            $socioNormalized = $socioMap[strtoupper($socioNombre)] ?? $socioNombre;
            $socioId = $socios[$socioNormalized] ?? null;
            if (!$socioId) {
                $this->command->warn("  Socio no encontrado: '$socioNombre' (normalizado: '$socioNormalized'), saltando factura #$nroFactura");
                continue;
            }

            // Buscar o crear cliente
            $clienteId = $clientes[$clienteNombre] ?? null;
            if (!$clienteId) {
                $cliente = Cliente::firstOrCreate(
                    ['razon_social' => $clienteNombre],
                    ['ruc' => strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $clienteNombre), 0, 10)), 'activo' => true]
                );
                $clienteId = $cliente->id;
                $clientes[$clienteNombre] = $clienteId;
                $this->command->info("  Cliente creado: '$clienteNombre' (id=$clienteId)");
            }

            // Crear factura
            $factura = Factura::create([
                'numero_factura' => (string)$nroFactura,
                'fecha_emision' => $fechaDefault,
                'socio_id' => $socioId,
                'cliente_id' => $clienteId,
                'valor_bruto' => $valorBruto,
                'valor_recibido' => $valorRecibido,
                'estado_factura' => $estado,
                'created_by' => $adminId,
            ]);

            // Retencion Turismo 3% (solo si hay valor)
            if ($ret3 > 0) {
                FacturaRetencion::create([
                    'factura_id' => $factura->id,
                    'tipo_retencion_id' => $retencion3,
                    'porcentaje' => 3.00,
                    'base_calculo' => $valorRecibido,
                    'valor_retencion' => $ret3,
                ]);
            }

            // Distribucion 1%
            if ($ret1 !== null && $ret1 > 0) {
                FacturaDistribucion::create([
                    'factura_id' => $factura->id,
                    'socio_destino_id' => $socioId,
                    'tipo_distribucion' => 'interna',
                    'valor' => round($ret1, 2),
                    'porcentaje' => 1.00,
                    'observacion' => 'Distribucion 1%',
                ]);
            }
        }
    }

    // ---- 8. FACTURAS ABRIL ----
    private function importarLiquidacionAbril()
    {
        // [nro_factura, socio, cliente, valor_bruto, ret_1%, v_recibido, ret_3%, estado]
        $facturas = [
            [213, 'FREDDY DELGADO', 'ALEX PAREDES', 180, null, 180.00, 5.40, 'pendiente'],
            [214, 'NELSON JIMENEZ', 'ECUANATICA S.A', 374, 3.74, 370.26, 11.1078, 'PAGADO'],
            [215, 'NELSON JIMENEZ', 'ECUANATICA S.A', 303, 3.03, 299.97, 8.9991, 'PAGADO'],
            [216, 'NELSON JIMENEZ', 'ECUANATICA S.A', 301, 3.01, 297.99, 8.9397, 'PAGADO'],
            [217, 'FREDDY DELGADO', 'UNIVERSIDAD UTE', 900, 9, 891.00, 26.73, 'pendiente'],
            [218, 'FREDDY DELGADO', 'NARWELL TOURS', 640, null, 640.00, 19.20, 'pendiente'],
            [219, 'FREDDY DELGADO', 'ANDESPORTS S.A.', 90, 0.9, 89.10, 2.673, 'PAGADO'],
            [220, 'FREDDY DELGADO', 'VANSERTRANS S.A', 400, 4, 396.00, 11.88, 'pendiente'],
        ];

        $this->importarFacturas('Abril', '2026-04-30', $facturas);
    }

    // ---- 9. FACTURAS MAYO ----
    private function importarLiquidacionMayo()
    {
        $facturas = [
            [221, 'NELSON JIMENEZ', 'GUAYATUNA S.A', 675, 6.75, 668.25, 20.0475, 'PAGADO'],
            [222, 'NELSON JIMENEZ', 'ECUANATICA S.A', 80, 0.8, 79.20, 2.376, 'PAGADO'],
            [223, 'NELSON JIMENEZ', 'ECUANATICA S.A', 80, 0.8, 79.20, 2.376, 'PAGADO'],
            // #224 = ANULADA (se salta)
            [225, 'LETTY WILLIAMS', 'HOTEL ORO VERDE MANDA', 400, 4, 396.00, 11.88, 'PAGADO'],
            [226, 'FREDDY DELGADO', 'TRAVEL AGENCY MEETECUADOR', 650, 6.5, 643.50, 19.305, 'pendiente'],
            [227, 'FREDDY DELGADO', 'CARRILLO CHICO REGULO', 1080, 10.8, 1069.20, 32.076, 'pendiente'],
        ];

        $this->importarFacturas('Mayo', '2026-05-31', $facturas);
    }

    // ---- 10. FACTURAS JUNIO ----
    private function importarLiquidacionJunio()
    {
        $facturas = [
            [228, 'FREDDY DELGADO', 'VALDIVIESO TAPIA IAN', 230, null, 230.00, 6.90, 'pendiente'],
            [229, 'NELSON JIMENEZ', 'GUAYATUNA S.A', 477.5, 4.775, 472.725, 11.18, 'PAGADA'],
            [231, 'FREDDY DELGADO', 'SWEADEN S.A', 600, 6, 594.00, 17.82, 'PAGADA'],
            [236, 'NELSON JIMENEZ', 'ECUANATICA S.A', 462, 4.62, 457.38, 13.7214, 'pendiente'],
            [238, 'LETTY WILLIAMS', 'CHIGUANO YANGUISELA MARCO', 220, null, 220.00, 6.60, 'PAGADA'],
        ];

        $this->importarFacturas('Junio', '2026-06-30', $facturas);
    }

    // ---- HELPER: Importar reporte de caja generico ----
    private function importarReporte(string $label, int $mes, string $fecha, float $vr, array $aportes, array $gastos): void
    {
        $this->command->info("Importando Reporte de Caja - $label...");

        $socios = Socio::pluck('id', 'nombres');
        $categorias = CategoriaMovimiento::pluck('id', 'nombre');
        $adminId = 1;

        $socioMap = [
            'LETTY WILLIAMS' => "Letty's Williams",
            "LETTY'S WILLIAMS" => "Letty's Williams",
            'GUSTAVO BUÑAY' => 'Gustavo Bunay',
            'GUSTAVO BUNAY' => 'Gustavo Bunay',
        ];

        // --- APORTES ---
        foreach ($aportes as $a) {
            $nombreSocio = $a[0];
            $socioNormalized = $socioMap[strtoupper($nombreSocio)] ?? $nombreSocio;
            $socioId = $socios[$socioNormalized] ?? null;

            if (!$socioId) {
                $this->command->warn("  Socio no encontrado para aporte: '$nombreSocio'");
                continue;
            }

            $existe = AporteSocio::where('socio_id', $socioId)
                ->where('periodo_mes', $mes)
                ->where('periodo_anio', 2026)
                ->exists();

            if ($existe) {
                $this->command->warn("  Aporte de '$nombreSocio' para mes $mes ya existe, saltando...");
                continue;
            }

            AporteSocio::create([
                'socio_id' => $socioId,
                'periodo_mes' => $mes,
                'periodo_anio' => 2026,
                'valor_cuota' => $a[1] ?? 0,
                'estado_pago' => $a[3],
                'fecha_pago' => $a[2] ? $this->excelSerialToDate($a[2]) : null,
            ]);
        }

        // --- INGRESO: Valor Recibido ---
        $catVR = $categorias['Valor recibido facturas'];
        $existeVR = MovimientoCaja::where('descripcion', "VALOR RECIBIDO FACTURAS - $label")
            ->where('tipo', 'ingreso')
            ->exists();
        if (!$existeVR) {
            MovimientoCaja::create([
                'fecha' => $fecha,
                'tipo' => 'ingreso',
                'categoria_id' => $catVR,
                'descripcion' => "VALOR RECIBIDO FACTURAS - $label",
                'valor' => $vr,
                'created_by' => $adminId,
            ]);
        } else {
            $this->command->warn("  Ingreso VR para $label ya existe, saltando...");
        }

        // --- GASTOS ---
        foreach ($gastos as $g) {
            $catId = $categorias[$g[1]] ?? null;
            if (!$catId) {
                $this->command->warn("  Categoria no encontrada: '{$g[1]}'");
                continue;
            }

            $existeGasto = MovimientoCaja::where('descripcion', $g[0])
                ->where('tipo', 'egreso')
                ->where('categoria_id', $catId)
                ->exists();
            if (!$existeGasto) {
                MovimientoCaja::create([
                    'fecha' => $fecha,
                    'tipo' => 'egreso',
                    'categoria_id' => $catId,
                    'descripcion' => $g[0],
                    'valor' => $g[2],
                    'created_by' => $adminId,
                ]);
            } else {
                $this->command->warn("  Gasto '{$g[0]}' ya existe, saltando...");
            }
        }
    }

    // ---- 11. REPORTE ABRIL ----
    private function importarReporteAbril()
    {
        $aportes = [
            ['Nelson Jimenez', 75, 46121, 'pagado'],
            ['Gustavo Bunay', 75, 46188, 'pagado'],
            ["Letty's Williams", 75, 46117, 'pagado'],
            ['Freddy Delgado', 150, 46117, 'pagado'],
            ['Eliana Mero', 0, null, 'exento'],
            ['Rogelio Molina', 75, 46117, 'pagado'],
        ];

        $gastos = [
            ['Emision de facturas', 'Emision de facturas', 35.00],
            ['PAGO DE DECLARACION DE IMPUESTOS', 'Declaracion Impuestos', 162.32],
            ['PAGO DE DECLARACION DE IVA', 'Declaracion IVA', 5.25],
            ['PAGO DE DECLARACION DE RETENCION', 'Declaracion Retencion', 1.50],
            ['PAGO AL CONTADOR-ABRIL', 'Pago a contador', 165.00],
            ['Movilizacion', 'Movilizacion', 20.00],
            ['Certificado Bancario', 'Certificado Bancario', 2.59],
            ['Servicios Bancarios', 'Servicios bancarios', 2.98],
        ];

        $this->importarReporte('ABRIL', 4, '2026-04-30', 1140.24, $aportes, $gastos);
    }

    // ---- 12. REPORTE MAYO ----
    private function importarReporteMayo()
    {
        $aportes = [
            ['Nelson Jimenez', 75, 46143, 'pagado'],
            ['Gustavo Bunay', 75, 46188, 'pagado'],
            ["Letty's Williams", 75, 46143, 'pagado'],
            ['Freddy Delgado', 150, 46143, 'pagado'],
            ['Eliana Mero', 0, null, 'exento'],
            ['Rogelio Molina', 75, 46143, 'pagado'],
        ];

        $gastos = [
            ['PAGO DE RETENCION DE FEBRERO 2023', 'Retencion anos anteriores', 19.54],
            ['PAGO DE RETENCION DE MAYO 2024', 'Retencion anos anteriores', 11.39],
            ['OBLIGACION DE SUPER DE COMPAÑIAS', 'Super de Companias', 89.66],
            ['PAGO AL CONTADOR - MES DE MAYO', 'Pago a contador', 165.00],
            ['EMISION DE FACTURAS', 'Emision de facturas', 35.00],
            ['MOVILIZACION', 'Movilizacion', 20.00],
            ['SERVICIOS BANCARIOS', 'Servicios bancarios', 2.16],
        ];

        $this->importarReporte('MAYO', 5, '2026-05-31', 1290.53, $aportes, $gastos);
    }

    // ---- 13. REPORTE JUNIO ----
    private function importarReporteJunio()
    {
        $aportes = [
            ['Nelson Jimenez', 75, 46175, 'pagado'],
            ['Gustavo Bunay', 75, 46188, 'pagado'],
            ["Letty's Williams", 75, 46185, 'pagado'],
            ['Freddy Delgado', 150, 46185, 'pagado'],
            ['Eliana Mero', 0, null, 'exento'],
            ['Rogelio Molina', 75, 46185, 'pagado'],
        ];

        $gastos = [
            ['TRANMITE DE PATENTES Y LICENCIAS', 'Patentes y Licencias', 25.00],
            ['PAGO AL CONTADOR-MES DE JUNIO', 'Pago a contador', 165.00],
            ['HOJAS Y TINTA PARA DOCUMENTOS ANT', 'Hojas y tinta', 5.00],
            ['PAGO PARA TRANMITE DE BOMBEROS', 'Tramite Bomberos', 20.00],
            ['EMISION DE FACTURAS', 'Emision de facturas', 35.00],
            ['MOVILIZACION', 'Movilizacion', 20.00],
            ['SERVICIOS BANCARIOS', 'Servicios bancarios', 2.46],
        ];

        $this->importarReporte('JUNIO', 6, '2026-06-30', 1485.84, $aportes, $gastos);
    }
}

<?php

use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        FacturaRetencion::whereNull('tipo_retencion_id')->delete();
        Schema::table('factura_retenciones', function (Blueprint $table) {
            if (Schema::hasColumn('factura_retenciones', 'nombre')) {
                $table->dropColumn('nombre');
            }
            $table->foreignId('tipo_retencion_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('factura_retenciones', function (Blueprint $table) {
            if (!Schema::hasColumn('factura_retenciones', 'nombre')) {
                $table->string('nombre')->nullable()->after('factura_id');
            }
            $table->foreignId('tipo_retencion_id')->nullable()->change();
        });
    }
};

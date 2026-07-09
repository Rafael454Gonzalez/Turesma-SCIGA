<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('factura_distribuciones', function (Blueprint $table) {
            $table->decimal('base_calculo', 12, 2)->nullable()->after('porcentaje');
        });
    }

    public function down(): void
    {
        Schema::table('factura_distribuciones', function (Blueprint $table) {
            $table->dropColumn('base_calculo');
        });
    }
};

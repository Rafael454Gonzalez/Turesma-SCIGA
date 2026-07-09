<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_retenciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tipo_retencion_id')->constrained('tipos_retencion')->restrictOnDelete();
            $table->decimal('porcentaje', 5, 2);
            $table->decimal('base_calculo', 12, 2);
            $table->decimal('valor_retencion', 12, 2);
            $table->string('estado')->default('activo');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_retenciones');
    }
};

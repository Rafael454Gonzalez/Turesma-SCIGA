<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aportes_socios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('periodo_mes');
            $table->unsignedSmallInteger('periodo_anio');
            $table->decimal('valor_cuota', 12, 2)->default(0);
            $table->string('estado_pago')->default('pendiente'); // pendiente, pagado, cancelado, exento
            $table->date('fecha_pago')->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aportes_socios');
    }
};

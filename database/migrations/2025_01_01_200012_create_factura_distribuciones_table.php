<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('factura_distribuciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('factura_id')->constrained()->cascadeOnDelete();
            $table->foreignId('socio_destino_id')->constrained('socios')->restrictOnDelete();
            $table->string('tipo_distribucion')->default('interna'); // interna, comision, reparto
            $table->decimal('valor', 12, 2);
            $table->decimal('porcentaje', 5, 2)->nullable();
            $table->text('observacion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('factura_distribuciones');
    }
};

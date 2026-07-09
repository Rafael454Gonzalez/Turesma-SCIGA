<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('socio_id')->constrained()->restrictOnDelete();
            $table->unsignedTinyInteger('periodo_mes');
            $table->unsignedSmallInteger('periodo_anio');
            $table->decimal('total_facturado', 12, 2);
            $table->decimal('total_retenciones', 12, 2);
            $table->decimal('total_distribuciones', 12, 2)->nullable();
            $table->decimal('total_neto', 12, 2);
            $table->string('estado')->default('borrador'); // borrador, emitido, aprobado, cerrado
            $table->text('firma_socio')->nullable();
            $table->date('fecha_generacion');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
    }
};

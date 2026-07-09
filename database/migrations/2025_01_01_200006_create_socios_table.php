<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('socios', function (Blueprint $table) {
            $table->id();
            $table->string('nombres');
            $table->string('identificacion')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->text('direccion')->nullable();
            $table->decimal('cuota_mensual_base', 12, 2)->default(0);
            $table->decimal('porcentaje_participacion', 5, 2)->nullable();
            $table->string('tipo_socio')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamp('fecha_registro')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('socios');
    }
};

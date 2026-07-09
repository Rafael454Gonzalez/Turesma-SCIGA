<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_factura');
            $table->date('fecha_emision');
            $table->foreignId('socio_id')->constrained()->restrictOnDelete();
            $table->foreignId('cliente_id')->constrained()->restrictOnDelete();
            $table->decimal('valor_bruto', 12, 2);
            $table->decimal('valor_recibido', 12, 2)->nullable()->comment('Monto real despues de retencion 1%');
            $table->string('estado_factura')->default('pendiente'); // pendiente, pagado, anulado
            $table->text('observacion')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};

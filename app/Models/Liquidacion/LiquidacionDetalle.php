<?php

namespace App\Models\Liquidacion;

use App\Models\Facturacion\Factura;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LiquidacionDetalle extends Model
{
    protected $fillable = [
        'liquidacion_id',
        'factura_id',
        'importe_aplicado',
        'observacion',
    ];

    public function liquidacion(): BelongsTo
    {
        return $this->belongsTo(Liquidacion::class);
    }

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }
}

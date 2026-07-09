<?php

namespace App\Models\Facturacion;

use App\Models\Catalogos\TipoRetencion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturaRetencion extends Model
{
    protected $table = 'factura_retenciones';

    protected $fillable = [
        'factura_id',
        'tipo_retencion_id',
        'porcentaje',
        'base_calculo',
        'valor_retencion',
        'estado',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    public function tipoRetencion(): BelongsTo
    {
        return $this->belongsTo(TipoRetencion::class, 'tipo_retencion_id');
    }
}

<?php

namespace App\Models\Facturacion;

use App\Models\Catalogos\Socio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacturaDistribucion extends Model
{
    protected $table = 'factura_distribuciones';

    protected $fillable = [
        'factura_id',
        'socio_destino_id',
        'tipo_distribucion',
        'valor',
        'porcentaje',
        'base_calculo',
        'observacion',
    ];

    public function factura(): BelongsTo
    {
        return $this->belongsTo(Factura::class);
    }

    public function socioDestino(): BelongsTo
    {
        return $this->belongsTo(Socio::class, 'socio_destino_id');
    }
}

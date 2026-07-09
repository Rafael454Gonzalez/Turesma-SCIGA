<?php

namespace App\Models\Catalogos;

use App\Models\Facturacion\FacturaRetencion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoRetencion extends Model
{
    protected $table = 'tipos_retencion';

    protected $fillable = [
        'nombre',
        'slug',
        'porcentaje',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function retenciones(): HasMany
    {
        return $this->hasMany(FacturaRetencion::class, 'tipo_retencion_id');
    }
}

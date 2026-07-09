<?php

namespace App\Models\Catalogos;

use App\Models\Facturacion\Factura;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'razon_social',
        'ruc',
        'contacto',
        'telefono',
        'email',
        'direccion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }
}

<?php

namespace App\Models\Catalogos;

use App\Models\Caja\AporteSocio;
use App\Models\Facturacion\Factura;
use App\Models\Facturacion\FacturaDistribucion;
use App\Models\Liquidacion\Liquidacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Socio extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nombres',
        'identificacion',
        'telefono',
        'email',
        'direccion',
        'cuota_mensual_base',
        'porcentaje_participacion',
        'tipo_socio',
        'activo',
        'fecha_registro',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'fecha_registro' => 'datetime',
        ];
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function facturas(): HasMany
    {
        return $this->hasMany(Factura::class);
    }

    public function distribuciones(): HasMany
    {
        return $this->hasMany(FacturaDistribucion::class, 'socio_destino_id');
    }

    public function liquidaciones(): HasMany
    {
        return $this->hasMany(Liquidacion::class);
    }

    public function aportes(): HasMany
    {
        return $this->hasMany(AporteSocio::class);
    }
}

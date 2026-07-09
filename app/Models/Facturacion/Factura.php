<?php

namespace App\Models\Facturacion;

use App\Models\Catalogos\Cliente;
use App\Models\Catalogos\Socio;
use App\Models\Liquidacion\LiquidacionDetalle;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero_factura',
        'fecha_emision',
        'socio_id',
        'cliente_id',
        'valor_bruto',
        'valor_recibido',
        'estado_factura',
        'observacion',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'date',
        ];
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }

    public function retenciones(): HasMany
    {
        return $this->hasMany(FacturaRetencion::class);
    }

    public function distribuciones(): HasMany
    {
        return $this->hasMany(FacturaDistribucion::class);
    }

    public function detallesLiquidacion(): HasMany
    {
        return $this->hasMany(LiquidacionDetalle::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

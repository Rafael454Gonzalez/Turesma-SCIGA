<?php

namespace App\Models\Liquidacion;

use App\Models\Catalogos\Socio;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Liquidacion extends Model
{
    protected $table = 'liquidaciones';

    protected $fillable = [
        'socio_id',
        'periodo_mes',
        'periodo_anio',
        'total_facturado',
        'total_retenciones',
        'total_distribuciones',
        'total_neto',
        'estado',
        'firma_socio',
        'fecha_generacion',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_generacion' => 'date',
        ];
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }

    public function detalles(): HasMany
    {
        return $this->hasMany(LiquidacionDetalle::class);
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

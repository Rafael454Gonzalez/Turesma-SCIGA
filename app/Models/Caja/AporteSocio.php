<?php

namespace App\Models\Caja;

use App\Models\Catalogos\Socio;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AporteSocio extends Model
{
    protected $table = 'aportes_socios';

    protected $fillable = [
        'socio_id',
        'periodo_mes',
        'periodo_anio',
        'valor_cuota',
        'estado_pago',
        'fecha_pago',
        'observacion',
    ];

    protected function casts(): array
    {
        return [
            'fecha_pago' => 'date',
        ];
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }
}

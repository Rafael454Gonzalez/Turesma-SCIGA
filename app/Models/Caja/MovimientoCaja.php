<?php

namespace App\Models\Caja;

use App\Models\Catalogos\CategoriaMovimiento;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoCaja extends Model
{
    protected $table = 'movimientos_caja';

    protected $fillable = [
        'fecha',
        'tipo',
        'categoria_id',
        'descripcion',
        'valor',
        'referencia_tipo',
        'referencia_id',
        'estado',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(CategoriaMovimiento::class, 'categoria_id');
    }

    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

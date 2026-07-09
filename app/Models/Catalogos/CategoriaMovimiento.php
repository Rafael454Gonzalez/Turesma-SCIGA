<?php

namespace App\Models\Catalogos;

use App\Models\Caja\MovimientoCaja;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriaMovimiento extends Model
{
    protected $table = 'categorias_movimiento';

    protected $fillable = [
        'nombre',
        'tipo',
        'descripcion',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoCaja::class, 'categoria_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ArchivoAdjunto extends Model
{
    protected $table = 'archivos_adjuntos';

    protected $fillable = [
        'archivable_type',
        'archivable_id',
        'nombre_original',
        'ruta',
        'mime_type',
        'tamano',
    ];

    public function archivable(): MorphTo
    {
        return $this->morphTo();
    }
}

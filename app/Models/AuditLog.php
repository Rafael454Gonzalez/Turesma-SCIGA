<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    protected $fillable = [
        'user_id',
        'accion',
        'auditable_type',
        'auditable_id',
        'valores_anteriores',
        'valores_nuevos',
        'direccion_ip',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'valores_anteriores' => 'json',
            'valores_nuevos' => 'json',
        ];
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }
}

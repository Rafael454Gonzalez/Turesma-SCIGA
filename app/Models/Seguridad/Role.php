<?php

namespace App\Models\Seguridad;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'activo',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permisos(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}

<?php

namespace App\Models;

use App\Models\Catalogos\Socio;
use App\Models\Seguridad\Role;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function rolPrincipal(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function socio(): BelongsTo
    {
        return $this->belongsTo(Socio::class);
    }

    protected ?Collection $permisosCache = null;

    public function hasPermission(string $slug): bool
    {
        if ($this->permisosCache === null) {
            $this->permisosCache = $this->roles()
                ->with('permisos')
                ->get()
                ->flatMap(fn($r) => $r->permisos->pluck('slug'));
        }
        return $this->permisosCache->contains($slug);
    }
}

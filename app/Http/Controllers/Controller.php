<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function authorizePermission(string $permission): void
    {
        if (!auth()->user()?->hasPermission($permission)) {
            abort(403, "No tienes permiso para realizar esta acción.");
        }
    }
}

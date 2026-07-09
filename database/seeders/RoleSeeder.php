<?php

namespace Database\Seeders;

use App\Models\Seguridad\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'nombre' => 'Super Administrador',
            'slug' => 'super-admin',
            'descripcion' => 'Acceso total al sistema',
            'activo' => true,
        ]);

        Role::create([
            'nombre' => 'Administrador',
            'slug' => 'admin',
            'descripcion' => 'Gestiona usuarios, facturas y liquidaciones',
            'activo' => true,
        ]);

        Role::create([
            'nombre' => 'Socio',
            'slug' => 'socio',
            'descripcion' => 'Ve sus propias facturas y liquidaciones',
            'activo' => true,
        ]);

        Role::create([
            'nombre' => 'Contador',
            'slug' => 'contador',
            'descripcion' => 'Acceso a caja, gastos y reportes contables',
            'activo' => true,
        ]);
    }
}

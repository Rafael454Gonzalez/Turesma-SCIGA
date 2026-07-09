<?php

namespace Database\Seeders;

use App\Models\Seguridad\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permisos = [
            // Usuarios
            ['nombre' => 'Ver usuarios', 'slug' => 'ver-usuarios', 'descripcion' => 'Ver listado de usuarios'],
            ['nombre' => 'Crear usuarios', 'slug' => 'crear-usuarios', 'descripcion' => 'Crear nuevos usuarios'],
            ['nombre' => 'Editar usuarios', 'slug' => 'editar-usuarios', 'descripcion' => 'Editar usuarios existentes'],
            ['nombre' => 'Eliminar usuarios', 'slug' => 'eliminar-usuarios', 'descripcion' => 'Eliminar usuarios'],

            // Socios
            ['nombre' => 'Ver socios', 'slug' => 'ver-socios', 'descripcion' => 'Ver listado de socios'],
            ['nombre' => 'Crear socios', 'slug' => 'crear-socios', 'descripcion' => 'Crear nuevos socios'],
            ['nombre' => 'Editar socios', 'slug' => 'editar-socios', 'descripcion' => 'Editar socios existentes'],

            // Clientes
            ['nombre' => 'Ver clientes', 'slug' => 'ver-clientes', 'descripcion' => 'Ver listado de clientes'],
            ['nombre' => 'Crear clientes', 'slug' => 'crear-clientes', 'descripcion' => 'Crear nuevos clientes'],
            ['nombre' => 'Editar clientes', 'slug' => 'editar-clientes', 'descripcion' => 'Editar clientes existentes'],

            // Facturas
            ['nombre' => 'Ver facturas', 'slug' => 'ver-facturas', 'descripcion' => 'Ver listado de facturas'],
            ['nombre' => 'Crear facturas', 'slug' => 'crear-facturas', 'descripcion' => 'Crear nuevas facturas'],
            ['nombre' => 'Editar facturas', 'slug' => 'editar-facturas', 'descripcion' => 'Editar facturas'],
            ['nombre' => 'Anular facturas', 'slug' => 'anular-facturas', 'descripcion' => 'Anular facturas'],

            // Liquidaciones
            ['nombre' => 'Ver liquidaciones', 'slug' => 'ver-liquidaciones', 'descripcion' => 'Ver liquidaciones'],
            ['nombre' => 'Crear liquidaciones', 'slug' => 'crear-liquidaciones', 'descripcion' => 'Generar liquidaciones'],
            ['nombre' => 'Aprobar liquidaciones', 'slug' => 'aprobar-liquidaciones', 'descripcion' => 'Aprobar liquidaciones'],

            // Caja
            ['nombre' => 'Ver movimientos', 'slug' => 'ver-movimientos', 'descripcion' => 'Ver movimientos de caja'],
            ['nombre' => 'Crear movimientos', 'slug' => 'crear-movimientos', 'descripcion' => 'Registrar movimientos de caja'],
            ['nombre' => 'Cerrar caja', 'slug' => 'cerrar-caja', 'descripcion' => 'Realizar cierres mensuales'],

            // Reportes
            ['nombre' => 'Ver reportes', 'slug' => 'ver-reportes', 'descripcion' => 'Ver reportes y exportar'],
        ];

        foreach ($permisos as $p) {
            Permission::create($p);
        }
    }
}

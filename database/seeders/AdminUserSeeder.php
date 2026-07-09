<?php

namespace Database\Seeders;

use App\Models\Seguridad\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('slug', 'super-admin')->first();

        $admin = User::create([
            'name' => 'Administrador',
            'email' => 'admin@admin.com',
            'password' => Hash::make('12345678'),
            'role_id' => $adminRole->id,
            'activo' => true,
        ]);

        $admin->roles()->attach($adminRole->id);
    }
}

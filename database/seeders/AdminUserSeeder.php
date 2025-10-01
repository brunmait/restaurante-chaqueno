<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('nombre_rol', 'administrador')->first();
        if (! $adminRole) {
            $adminRole = Role::create(['nombre_rol' => 'administrador', 'descripcion' => 'Acceso a gestión de usuarios y stock']);
        }

        User::firstOrCreate(
            ['email' => 'admin@rinconchaqueno.com'],
            [
                'nombre' => 'Administrador',
                'contraseña' => Hash::make('admin1234'),
                'rol_id' => $adminRole->id,
            ]
        );
    }
}




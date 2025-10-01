<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['nombre_rol' => 'administrador', 'descripcion' => 'Acceso a gestión de usuarios y stock'],
            ['nombre_rol' => 'cajero', 'descripcion' => 'Acceso a caja y ventas'],
            ['nombre_rol' => 'dueno', 'descripcion' => 'Acceso total y reportes'],
        ];

        foreach ($roles as $data) {
            Role::firstOrCreate(['nombre_rol' => $data['nombre_rol']], $data);
        }
    }
}



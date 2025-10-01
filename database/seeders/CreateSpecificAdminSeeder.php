<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateSpecificAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Detectar columnas reales de la tabla roles
        $roleNameCol = Schema::hasColumn('roles', 'nombre_rol') ? 'nombre_rol' : (Schema::hasColumn('roles', 'name') ? 'name' : null);
        $roleDescCol = Schema::hasColumn('roles', 'descripcion') ? 'descripcion' : (Schema::hasColumn('roles', 'description') ? 'description' : null);

        if ($roleNameCol === null) {
            throw new \RuntimeException('La tabla roles no tiene columna de nombre (nombre_rol/name).');
        }

        // Buscar o crear rol administrador
        $adminRoleId = DB::table('roles')->where($roleNameCol, 'administrador')->value('id');
        if (!$adminRoleId) {
            $adminRoleId = DB::table('roles')->insertGetId([
                $roleNameCol => 'administrador',
                $roleDescCol ?? 'descripcion' => 'Administrador del sistema',
            ]);
        }

        // Asegurar que exista la tabla 'usuarios' con columnas esperadas
        if (!Schema::hasTable('usuarios')) {
            Schema::create('usuarios', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->string('email')->unique();
                $table->string('contraseña');
                $table->unsignedBigInteger('rol_id')->nullable();
                // sin timestamps porque el modelo los tiene deshabilitados
            });
        }

        $usersTable = 'usuarios';

        // Detectar nombre real de la columna contraseña: 'contrasena' o 'contraseña'
        $pwdCol = Schema::hasColumn($usersTable, 'contrasena') ? 'contrasena' : (Schema::hasColumn($usersTable, 'contraseña') ? 'contraseña' : null);
        if ($pwdCol === null) {
            // Si no existe ninguna, crear 'contraseña'
            Schema::table($usersTable, function (Blueprint $table) {
                if (!Schema::hasColumn('usuarios', 'contraseña')) {
                    $table->string('contraseña');
                }
            });
            $pwdCol = 'contraseña';
        }

        // Insertar usuario admin si no existe
        $adminEmail = 'admin@rinconchaqueno.com';
        $exists = DB::table($usersTable)->where('email', $adminEmail)->exists();
        if (!$exists) {
            DB::table($usersTable)->insert([
                'nombre' => 'Administrador',
                'email' => $adminEmail,
                $pwdCol => Hash::make('admin1234'),
                'rol_id' => $adminRoleId,
            ]);
        }
    }
}



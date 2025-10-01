<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🔧 Creando datos correctos...\n";

// Limpiar datos existentes
DB::table('usuarios')->delete();
DB::table('roles')->delete();

// Insertar roles con estructura correcta (name, description)
$adminRoleId = DB::table('roles')->insertGetId([
    'name' => 'admin',
    'description' => 'Administrador del sistema',
    'created_at' => now(),
    'updated_at' => now()
]);

$cajeroRoleId = DB::table('roles')->insertGetId([
    'name' => 'cajero',
    'description' => 'Cajero del restaurante', 
    'created_at' => now(),
    'updated_at' => now()
]);

echo "✅ Roles creados correctamente\n";

// Insertar usuarios
DB::table('usuarios')->insert([
    'nombre' => 'Administrador',
    'email' => 'admin@chaqueno.com',
    'contrasena' => Hash::make('admin123'),
    'rol_id' => $adminRoleId,
    'activo' => true
]);

DB::table('usuarios')->insert([
    'nombre' => 'Cajero Principal', 
    'email' => 'cajero@chaqueno.com',
    'contrasena' => Hash::make('cajero123'),
    'rol_id' => $cajeroRoleId,
    'activo' => true
]);

echo "✅ Usuarios creados:\n";
echo "   👨💼 Admin: admin@chaqueno.com / admin123\n";
echo "   👨💻 Cajero: cajero@chaqueno.com / cajero123\n";
echo "🚀 Datos creados correctamente!\n";
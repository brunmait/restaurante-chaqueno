<?php

use Illuminate\Support\Facades\DB;

echo "🔧 Corrigiendo datos de roles de manera segura...\n";

// Desactivar foreign key checks temporalmente
DB::statement('SET FOREIGN_KEY_CHECKS = 0');

// Limpiar datos existentes
DB::table('usuarios')->delete();
DB::table('roles')->delete();

// Insertar roles con estructura correcta
$adminRoleId = DB::table('roles')->insertGetId([
    'nombre_rol' => 'admin',
    'descripcion' => 'Administrador del sistema'
]);

$cajeroRoleId = DB::table('roles')->insertGetId([
    'nombre_rol' => 'cajero', 
    'descripcion' => 'Cajero del restaurante'
]);

echo "✅ Roles insertados correctamente\n";

// Insertar usuarios
DB::table('usuarios')->insert([
    'nombre' => 'Administrador',
    'email' => 'admin@chaqueno.com',
    'contrasena' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    'rol_id' => $adminRoleId,
    'activo' => true
]);

DB::table('usuarios')->insert([
    'nombre' => 'Cajero Principal',
    'email' => 'cajero@chaqueno.com',
    'contrasena' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
    'rol_id' => $cajeroRoleId,
    'activo' => true
]);

// Reactivar foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS = 1');

echo "✅ Usuarios creados:\n";
echo "   👨💼 Admin: admin@chaqueno.com / password\n";
echo "   👨💻 Cajero: cajero@chaqueno.com / password\n";
echo "🚀 Datos corregidos!\n";
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "🔧 Corrigiendo estructura de tabla roles...\n";

// Eliminar tabla roles actual
Schema::dropIfExists('roles');

// Recrear con la estructura correcta según tu modelo
Schema::create('roles', function (Blueprint $table) {
    $table->id();
    $table->string('nombre_rol');
    $table->string('descripcion')->nullable();
    // Sin timestamps según tu modelo
});

echo "✅ Tabla roles recreada con estructura correcta\n";

// Crear roles con la estructura correcta
$adminRoleId = DB::table('roles')->insertGetId([
    'nombre_rol' => 'admin',
    'descripcion' => 'Administrador del sistema'
]);

$cajeroRoleId = DB::table('roles')->insertGetId([
    'nombre_rol' => 'cajero',
    'descripcion' => 'Cajero del restaurante'
]);

echo "✅ Roles creados correctamente\n";

// Actualizar usuarios con los nuevos IDs de roles
DB::table('usuarios')->where('email', 'admin@chaqueno.com')->update(['rol_id' => $adminRoleId]);
DB::table('usuarios')->where('email', 'cajero@chaqueno.com')->update(['rol_id' => $cajeroRoleId]);

echo "✅ Usuarios actualizados con roles correctos\n";
echo "🚀 Estructura de roles corregida!\n";
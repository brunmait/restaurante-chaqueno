<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🔧 Corrigiendo estructura de tabla usuarios...\n";

// Eliminar tabla usuarios actual
Schema::dropIfExists('usuarios');

// Recrear con la estructura correcta según tu modelo
Schema::create('usuarios', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('email')->unique();
    $table->string('contrasena'); // Nota: sin ñ para evitar problemas
    $table->unsignedBigInteger('rol_id');
    $table->boolean('activo')->default(true);
    // Sin timestamps según tu modelo
    
    $table->foreign('rol_id')->references('id')->on('roles')->onDelete('cascade');
});

echo "✅ Tabla usuarios recreada con estructura correcta\n";

// Verificar roles
$adminRole = DB::table('roles')->where('name', 'admin')->first();
$cajeroRole = DB::table('roles')->where('name', 'cajero')->first();

// Crear usuarios con la estructura correcta
DB::table('usuarios')->insert([
    'nombre' => 'Administrador',
    'email' => 'admin@chaqueno.com',
    'contrasena' => Hash::make('admin123'),
    'rol_id' => $adminRole->id,
    'activo' => true
]);

DB::table('usuarios')->insert([
    'nombre' => 'Cajero Principal',
    'email' => 'cajero@chaqueno.com', 
    'contrasena' => Hash::make('cajero123'),
    'rol_id' => $cajeroRole->id,
    'activo' => true
]);

echo "✅ Usuarios creados:\n";
echo "   👨💼 Admin: admin@chaqueno.com / admin123\n";
echo "   👨💻 Cajero: cajero@chaqueno.com / cajero123\n";

echo "🚀 Estructura corregida!\n";
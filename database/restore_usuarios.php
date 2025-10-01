<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

echo "🔧 Recreando tabla usuarios...\n";

// Crear tabla usuarios
Schema::create('usuarios', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('email')->unique();
    $table->string('password');
    $table->foreignId('role_id')->constrained('roles')->onDelete('cascade');
    $table->boolean('activo')->default(true);
    $table->timestamps();
});

echo "✅ Tabla usuarios creada\n";

// Verificar si hay roles
$adminRole = DB::table('roles')->where('name', 'admin')->first();
$cajeroRole = DB::table('roles')->where('name', 'cajero')->first();

if (!$adminRole) {
    DB::table('roles')->insert([
        'name' => 'admin',
        'description' => 'Administrador del sistema',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    $adminRole = DB::table('roles')->where('name', 'admin')->first();
    echo "✅ Rol admin creado\n";
}

if (!$cajeroRole) {
    DB::table('roles')->insert([
        'name' => 'cajero', 
        'description' => 'Cajero del restaurante',
        'created_at' => now(),
        'updated_at' => now()
    ]);
    $cajeroRole = DB::table('roles')->where('name', 'cajero')->first();
    echo "✅ Rol cajero creado\n";
}

// Crear usuario administrador por defecto
DB::table('usuarios')->insert([
    'nombre' => 'Administrador',
    'email' => 'admin@chaqueno.com',
    'password' => Hash::make('admin123'),
    'role_id' => $adminRole->id,
    'activo' => true,
    'created_at' => now(),
    'updated_at' => now()
]);

// Crear cajero por defecto
DB::table('usuarios')->insert([
    'nombre' => 'Cajero Principal',
    'email' => 'cajero@chaqueno.com', 
    'password' => Hash::make('cajero123'),
    'role_id' => $cajeroRole->id,
    'activo' => true,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "✅ Usuarios por defecto creados:\n";
echo "   👨‍💼 Admin: admin@chaqueno.com / admin123\n";
echo "   👨‍💻 Cajero: cajero@chaqueno.com / cajero123\n";

echo "🚀 Restauración completada!\n";
<?php
// Script para limpiar base de datos desde Laravel
// Ejecutar: php artisan tinker
// Luego: include 'database/cleanup_command.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "🧹 Iniciando limpieza de base de datos...\n";

// Desactivar verificación de foreign keys
DB::statement('SET FOREIGN_KEY_CHECKS = 0');

$tablesToDrop = [
    'app_settings',
    'display_queue', 
    'failed_jobs',
    'password_resets',
    'payments',
    'pedidos',
    'personal_access_tokens',
    'stock',
    'usuarios',
    'ventas'
];

$droppedCount = 0;

foreach ($tablesToDrop as $table) {
    if (Schema::hasTable($table)) {
        Schema::drop($table);
        echo "✅ Eliminada tabla: $table\n";
        $droppedCount++;
    } else {
        echo "⚠️  Tabla no existe: $table\n";
    }
}

// Reactivar verificación de foreign keys
DB::statement('SET FOREIGN_KEY_CHECKS = 1');

echo "\n📊 Resumen:\n";
echo "- Tablas eliminadas: $droppedCount\n";

// Mostrar tablas restantes
$remainingTables = DB::select('SHOW TABLES');
echo "- Tablas restantes: " . count($remainingTables) . "\n\n";

echo "🎯 Tablas que deben quedar:\n";
$expectedTables = [
    'users', 'roles', 'categorias', 'productos', 
    'proveedores', 'compras', 'compra_items',
    'stock_costillas', 'ventas_costillas', 
    'pedidos_online', 'migrations'
];

foreach ($expectedTables as $table) {
    $exists = Schema::hasTable($table) ? '✅' : '❌';
    echo "$exists $table\n";
}

echo "\n🚀 Limpieza completada!\n";
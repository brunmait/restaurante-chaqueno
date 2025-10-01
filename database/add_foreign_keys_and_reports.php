<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

echo "🔧 Agregando foreign keys y tabla de reportes...\n";

// 1. Agregar foreign keys a ventas_costillas
Schema::table('ventas_costillas', function (Blueprint $table) {
    $table->foreignId('usuario_id')->nullable()->after('id')->constrained('usuarios')->onDelete('set null');
    $table->string('numero_ticket', 10)->nullable()->after('tipo');
    $table->index('numero_ticket');
});

echo "✅ Foreign keys agregadas a ventas_costillas\n";

// 2. Agregar foreign key a pedidos_online
Schema::table('pedidos_online', function (Blueprint $table) {
    $table->foreignId('usuario_id')->nullable()->after('id')->constrained('usuarios')->onDelete('set null');
});

echo "✅ Foreign key agregada a pedidos_online\n";

// 3. Agregar foreign key a stock_costillas
Schema::table('stock_costillas', function (Blueprint $table) {
    $table->foreignId('usuario_id')->nullable()->after('id')->constrained('usuarios')->onDelete('set null');
});

echo "✅ Foreign key agregada a stock_costillas\n";

// 4. Crear tabla de reportes
Schema::create('reportes', function (Blueprint $table) {
    $table->id();
    $table->string('tipo'); // 'ventas_diarias', 'stock_bajo', 'pedidos_pendientes', etc.
    $table->string('titulo');
    $table->json('datos'); // Datos del reporte en formato JSON
    $table->date('fecha_reporte');
    $table->decimal('total_ventas', 10, 2)->nullable();
    $table->integer('total_pedidos')->nullable();
    $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
    $table->timestamps();
    
    $table->index(['tipo', 'fecha_reporte']);
});

echo "✅ Tabla reportes creada\n";

// 5. Insertar algunos reportes de ejemplo
$adminId = DB::table('usuarios')->where('email', 'admin@chaqueno.com')->value('id');

DB::table('reportes')->insert([
    [
        'tipo' => 'ventas_diarias',
        'titulo' => 'Reporte de Ventas - ' . date('Y-m-d'),
        'datos' => json_encode([
            'ventas_costillas' => 0,
            'pedidos_online' => 0,
            'total_ingresos' => 0
        ]),
        'fecha_reporte' => date('Y-m-d'),
        'total_ventas' => 0,
        'total_pedidos' => 0,
        'usuario_id' => $adminId,
        'created_at' => now(),
        'updated_at' => now()
    ],
    [
        'tipo' => 'stock_costillas',
        'titulo' => 'Estado del Stock - ' . date('Y-m-d'),
        'datos' => json_encode([
            'costillas_disponibles' => 0,
            'stock_minimo' => 10,
            'necesita_reposicion' => true
        ]),
        'fecha_reporte' => date('Y-m-d'),
        'usuario_id' => $adminId,
        'created_at' => now(),
        'updated_at' => now()
    ]
]);

echo "✅ Reportes de ejemplo insertados\n";

echo "🚀 Proceso completado!\n";
echo "\n📊 RELACIONES CREADAS:\n";
echo "- ventas_costillas.usuario_id → usuarios.id\n";
echo "- ventas_costillas.numero_ticket ↔ pedidos_online.numero_ticket\n";
echo "- pedidos_online.usuario_id → usuarios.id\n";
echo "- stock_costillas.usuario_id → usuarios.id\n";
echo "- reportes.usuario_id → usuarios.id\n";
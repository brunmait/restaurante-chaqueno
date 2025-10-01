<?php

use Illuminate\Support\Facades\DB;

echo "🔧 Insertando reportes correctamente...\n";

$adminId = DB::table('usuarios')->where('email', 'admin@chaqueno.com')->value('id');

// Insertar reportes uno por uno
DB::table('reportes')->insert([
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
]);

DB::table('reportes')->insert([
    'tipo' => 'stock_costillas',
    'titulo' => 'Estado del Stock - ' . date('Y-m-d'),
    'datos' => json_encode([
        'costillas_disponibles' => 0,
        'stock_minimo' => 10,
        'necesita_reposicion' => true
    ]),
    'fecha_reporte' => date('Y-m-d'),
    'total_ventas' => null,
    'total_pedidos' => null,
    'usuario_id' => $adminId,
    'created_at' => now(),
    'updated_at' => now()
]);

echo "✅ Reportes insertados correctamente\n";
echo "🚀 Proceso completado!\n";
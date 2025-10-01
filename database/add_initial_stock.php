<?php

use Illuminate\Support\Facades\DB;

echo "🔧 Agregando stock inicial de costillas...\n";

// Verificar si ya hay stock
$stock = DB::table('stock_costillas')->first();

if ($stock) {
    // Actualizar stock existente
    DB::table('stock_costillas')
        ->where('id', $stock->id)
        ->update([
            'costillas_completas' => 3, // 3 costillares completos
            'costillas_disponibles' => 54, // 3 * 18 = 54 costillas individuales
            'costo_por_carne' => 700,
            'costo_promedio' => 38.89,
            'stock_minimo' => 10,
            'updated_at' => now()
        ]);
    echo "✅ Stock actualizado: 54 costillas disponibles\n";
} else {
    // Crear stock inicial
    DB::table('stock_costillas')->insert([
        'costillas_completas' => 3,
        'costillas_disponibles' => 54,
        'costo_por_carne' => 700,
        'costo_promedio' => 38.89,
        'stock_minimo' => 10,
        'created_at' => now(),
        'updated_at' => now()
    ]);
    echo "✅ Stock inicial creado: 54 costillas disponibles\n";
}

echo "🚀 Stock listo para ventas!\n";
<?php

use Illuminate\Support\Facades\DB;

echo "🧪 Creando pedido de ejemplo con múltiples platos...\n";

// Generar número de ticket único
$numeroTicket = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

// Crear pedido con múltiples platos y acompañamientos
$items = [
    [
        'cantidad' => 1,
        'precio' => 50,
        'acompanamiento' => 'arroz'
    ],
    [
        'cantidad' => 1.5,
        'precio' => 60,
        'acompanamiento' => 'mote'
    ],
    [
        'cantidad' => 2,
        'precio' => 70,
        'acompanamiento' => 'mixto'
    ]
];

$total = array_sum(array_column($items, 'precio'));

DB::table('pedidos_online')->insert([
    'numero_ticket' => $numeroTicket,
    'cliente_nombre' => 'Juan Pérez',
    'cliente_telefono' => '70123456',
    'items' => json_encode($items),
    'total' => $total,
    'estado' => 'pendiente',
    'fecha_pedido' => now(),
    'notas' => 'Pedido de ejemplo con múltiples platos',
    'created_at' => now(),
    'updated_at' => now()
]);

echo "✅ Pedido creado:\n";
echo "   Ticket: $numeroTicket\n";
echo "   Cliente: Juan Pérez\n";
echo "   Items:\n";
foreach($items as $item) {
    echo "     - {$item['cantidad']} costilla(s) - Bs.{$item['precio']} - {$item['acompanamiento']}\n";
}
echo "   Total: Bs.$total\n";
echo "🚀 Sistema de múltiples platos funcionando!\n";
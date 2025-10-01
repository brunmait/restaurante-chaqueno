<?php

use Illuminate\Support\Facades\DB;

echo "🔧 Actualizando pedidos sin acompañamientos...\n";

$pedidos = DB::table('pedidos_online')->get();

foreach($pedidos as $pedido) {
    $items = json_decode($pedido->items, true);
    $updated = false;
    
    foreach($items as &$item) {
        if(!isset($item['acompanamiento'])) {
            // Asignar acompañamiento aleatorio
            $acompanamientos = ['arroz', 'mote', 'mixto'];
            $item['acompanamiento'] = $acompanamientos[array_rand($acompanamientos)];
            $updated = true;
        }
    }
    
    if($updated) {
        DB::table('pedidos_online')
            ->where('id', $pedido->id)
            ->update(['items' => json_encode($items)]);
        echo "✅ Actualizado pedido: {$pedido->numero_ticket}\n";
    }
}

echo "🚀 Actualización completada!\n";
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ReporteController;

class CostillasController extends Controller
{
    // Panel de administración
    public function adminIndex()
    {
        $stock = DB::table('stock_costillas')->first();
        $ventas = DB::table('ventas_costillas')
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();
        
        return view('admin.costillas.index', compact('stock', 'ventas'));
    }

    // Agregar stock (admin)
    public function agregarStock(Request $request)
    {
        $request->validate([
            'cantidad' => 'required|numeric|min:0.1'
        ]);

        // Calcular costillas individuales (18 por costillar completo)
        $costillasIndividuales = $request->cantidad * 18;
        $costoPorCostilla = 700 / 18; // Precio fijo por ahora

        // Obtener stock actual
        $stockActual = DB::table('stock_costillas')->first();
        
        if ($stockActual) {
            // Actualizar stock existente
            DB::table('stock_costillas')
                ->where('id', $stockActual->id)
                ->update([
                    'costillas_completas' => $stockActual->costillas_completas + $request->cantidad,
                    'costillas_disponibles' => ($stockActual->costillas_disponibles ?? 0) + $costillasIndividuales,
                    'costo_promedio' => $costoPorCostilla,
                    'updated_at' => now()
                ]);
        } else {
            // Crear nuevo registro
            DB::table('stock_costillas')->insert([
                'costillas_completas' => $request->cantidad,
                'costillas_disponibles' => $costillasIndividuales,
                'costo_por_carne' => 700,
                'costo_promedio' => $costoPorCostilla,
                'stock_minimo' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        return back()->with('success', 'Stock agregado correctamente');
    }

    // Panel del cajero
    public function cajeroIndex()
    {
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? 0) : 0;
        
        // Si no hay stock_costillas, crear uno inicial
        if (!$stock) {
            DB::table('stock_costillas')->insert([
                'costillas_completas' => 0,
                'costillas_disponibles' => 0,
                'costo_por_carne' => 700,
                'costo_promedio' => 38.89,
                'stock_minimo' => 10,
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $costillasDisponibles = 0;
        }
        
        $precios = [
            50 => 1,
            60 => 1.5,
            70 => 2,
            80 => 2.5,
            90 => 3
        ];
        
        // Calcular disponibilidad por precio
        $disponibilidad = [];
        foreach ($precios as $precio => $cantidad) {
            $disponibilidad[$precio] = $costillasDisponibles >= floatval($cantidad);
        }

        return view('cashier.costillas.index', compact('costillasDisponibles', 'precios', 'disponibilidad'));
    }

    // Vender costillas (cajero)
    public function vender(Request $request)
    {
        $request->validate([
            'items' => 'required|string',
            'cliente_nombre' => 'nullable|string|max:100'
        ]);

        $items = json_decode($request->items, true);
        
        if (empty($items)) {
            return back()->withErrors('No hay items en el pedido');
        }

        $precios = [50 => 1, 60 => 1.5, 70 => 2, 80 => 2.5, 90 => 3];
        $totalCostillas = 0;
        $totalPrecio = 0;
        
        // Calcular totales y validar
        foreach ($items as $item) {
            $precio = $item['precio'];
            if (!isset($precios[$precio])) {
                return back()->withErrors('Precio inválido: ' . $precio);
            }
            $cantidad = $precios[$precio];
            $totalCostillas += floatval($cantidad);
            $totalPrecio += $precio;
        }

        // Verificar stock
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? 0) : 0;

        if ($costillasDisponibles < $totalCostillas) {
            return back()->withErrors('No hay suficientes costillas en stock. Disponibles: ' . $costillasDisponibles);
        }

        // Generar número de ticket único
        $numeroTicket = 'V' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Verificar que no exista
        while (DB::table('pedidos_online')->where('numero_ticket', $numeroTicket)->exists()) {
            $numeroTicket = 'V' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        DB::transaction(function () use ($items, $totalCostillas, $totalPrecio, $request, $stock, $numeroTicket) {
            // Registrar venta principal
            $ventaId = DB::table('ventas_costillas')->insertGetId([
                'cantidad_costillas' => $totalCostillas,
                'precio_unitario' => $totalPrecio / count($items),
                'total' => $totalPrecio,
                'cliente_nombre' => $request->cliente_nombre,
                'tipo' => 'venta_directa',
                'detalles' => json_encode($items),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Crear pedido en pantalla con ticket
            DB::table('pedidos_online')->insert([
                'numero_ticket' => $numeroTicket,
                'cliente_nombre' => 'Venta Directa - Cajero',
                'cliente_telefono' => 'N/A',
                'items' => json_encode($items),
                'total' => $totalPrecio,
                'estado' => 'listo',
                'fecha_pedido' => now(),
                'fecha_entrega' => now(),
                'notas' => 'Venta realizada en caja',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar stock
            $nuevoStock = ($stock->costillas_disponibles ?? 0) - $totalCostillas;
            DB::table('stock_costillas')
                ->where('id', $stock->id)
                ->update([
                    'costillas_disponibles' => $nuevoStock,
                    'updated_at' => now()
                ]);
            
            // Generar reporte de fin de día si el stock se agotó
            if ($nuevoStock <= 0) {
                $reporteController = new \App\Http\Controllers\ReporteController();
                $reporteController->generarReporteFinDia();
            }
        });

        $itemsTexto = collect($items)->map(function($item) {
            return $item['cantidad'] . ' costillas (' . $item['acompanamiento'] . ')';
        })->join(', ');

        return response()->json([
            'success' => true,
            'message' => "Venta registrada: {$itemsTexto} por Bs. {$totalPrecio}",
            'numero_ticket' => $numeroTicket,
            'comprobante_id' => DB::table('pedidos_online')->where('numero_ticket', $numeroTicket)->value('id'),
            'total' => $totalPrecio,
            'items' => $items
        ]);
    }

    // API para pedidos online
    public function stockDisponible()
    {
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? 0) : 0;
        
        $precios = [50 => 1, 60 => 1.5, 70 => 2, 80 => 2.5, 90 => 3];
        $disponibilidad = [];
        
        foreach ($precios as $precio => $cantidad) {
            $disponibilidad[$precio] = $costillasDisponibles >= floatval($cantidad) ? 1 : 0;
        }

        return response()->json([
            'costillas_disponibles' => $costillasDisponibles,
            'disponibilidad' => $disponibilidad
        ]);
    }
}
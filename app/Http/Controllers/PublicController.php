<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    public function home()
    {
        return view('public.home');
    }

    public function menu()
    {
        // Obtener solo costillas disponibles del nuevo sistema
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? $stock->costillas_completas * 18) : 0;
        
        $precios = [
            '1' => 50,
            '1.5' => 60,
            '2' => 70,
            '2.5' => 80,
            '3' => 90
        ];
        
        $platos = [];
        foreach ($precios as $cantidad => $precio) {
            $platos[] = (object) [
                'nombre' => "Costillas de Chancho - {$cantidad} " . ($cantidad == 1 ? 'costilla' : 'costillas'),
                'precio' => $precio,
                'stock' => $costillasDisponibles >= $cantidad ? 1 : 0,
                'categoria' => 'Carnes',
                'descripcion' => "Deliciosas costillas de chancho a la cruz, {$cantidad} " . ($cantidad == 1 ? 'costilla' : 'costillas')
            ];
        }

        return view('public.menu', compact('platos'));
    }

    // Método adicional para compatibilidad
    public function menuProductos()
    {
        $productos = DB::table('productos')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->select('productos.*', 'categorias.nombre as categoria_nombre')
            ->where('productos.precio', '>', 0)
            ->where('productos.disponible', 1)
            ->orderBy('categorias.nombre')
            ->orderBy('productos.nombre')
            ->get();
        
        return view('public.menu', compact('productos'));
    }

    private function getKilosPorPlatoChancho($nombrePlato)
    {
        // Definir cuántos kilos de carne se necesitan por plato
        $kilosPorPlato = [
            'Chancho a la Cruz - 2 Costillas' => 0.4,    // 400g por plato
            'Chancho a la Cruz - 2.5 Costillas' => 0.5,   // 500g por plato
            'Chancho a la Cruz - 3 Costillas' => 0.6,     // 600g por plato
            'Chancho a la Cruz - 3.5 Costillas' => 0.7,   // 700g por plato
        ];
        
        return $kilosPorPlato[$nombrePlato] ?? 0.5; // Default 500g
    }

    private function getPlatosPorPollo($nombrePlato)
    {
        // Definir cuántos platos se pueden hacer por pollo
        $platosPorPollo = [
            'Pollo a la Leña' => 1,           // 1 plato por pollo entero
            'Pollo a la Leña - Media Porción' => 2, // 2 medias porciones por pollo
        ];
        
        return $platosPorPollo[$nombrePlato] ?? 1;
    }

    public function promociones()
    {
        return view('public.promociones');
    }

    public function contacto()
    {
        return view('public.contacto');
    }

    public function orderForm()
    {
        return view('public.pedido_costillas');
    }

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'cliente_nombre' => ['required','string','max:100'],
            'cliente_telefono' => ['required','string','max:30'],
            'cliente_direccion' => ['nullable','string','max:200'],
            'items_json' => ['required','string'],
            'precio_total' => ['required','numeric','min:50'],
            'notas' => ['nullable','string','max:300'],
        ]);

        $items = json_decode($data['items_json'], true);
        
        if (empty($items)) {
            return back()->withErrors('No hay items en el pedido')->withInput();
        }

        // Calcular total de costillas necesarias
        $totalCostillas = 0;
        foreach ($items as $item) {
            $totalCostillas += floatval($item['cantidad']);
        }

        // Verificar stock
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? 0) : 0;

        if ($costillasDisponibles < $totalCostillas) {
            return back()->withErrors('No hay suficientes costillas en stock. Disponibles: ' . $costillasDisponibles)->withInput();
        }

        // Generar número de ticket único
        $numeroTicket = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        while (DB::table('pedidos_online')->where('numero_ticket', $numeroTicket)->exists()) {
            $numeroTicket = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        DB::transaction(function () use ($data, $items, $totalCostillas, $numeroTicket, $stock) {
            // Crear pedido online
            DB::table('pedidos_online')->insert([
                'numero_ticket' => $numeroTicket,
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_telefono' => $data['cliente_telefono'],
                'items' => json_encode($items),
                'total' => $data['precio_total'],
                'estado' => 'pendiente',
                'fecha_pedido' => now(),
                'notas' => $data['notas'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Registrar venta en ventas_costillas
            DB::table('ventas_costillas')->insert([
                'cantidad_costillas' => $totalCostillas,
                'precio_unitario' => $data['precio_total'] / count($items),
                'total' => $data['precio_total'],
                'cliente_nombre' => $data['cliente_nombre'],
                'tipo' => 'pedido_online',
                'numero_ticket' => $numeroTicket,
                'detalles' => json_encode($items),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Actualizar stock
            DB::table('stock_costillas')
                ->where('id', $stock->id)
                ->update([
                    'costillas_disponibles' => ($stock->costillas_disponibles ?? 0) - $totalCostillas,
                    'updated_at' => now()
                ]);
        });

        return redirect()->route('public.home')->with('success', 'Pedido realizado correctamente. Tu número de ticket es: ' . $numeroTicket);
    }

    public function pay(int $pedidoId)
    {
        $pedido = DB::table('pedidos')->where('id', $pedidoId)->first();
        abort_unless($pedido, 404);
        return view('public.pago', ['pedido' => $pedido]);
    }
}



<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class CashierController extends Controller
{
    // 📊 Mapa de platos que rinde UNA costilla según precio
    private $mapaPlatos = [
        50 => 16,  // 16 platos por costilla
        60 => 10,  // 10 platos por costilla
        70 => 8,   // 8 platos por costilla
        80 => 6,   // 6 platos por costilla
        90 => 4,   // 4 platos por costilla
    ];

    public function dashboard()
    {
        // Obtener stock de costillas del nuevo sistema
        $stock = DB::table('stock_costillas')->first();
        $costillasDisponibles = $stock ? ($stock->costillas_disponibles ?? 0) : 0;
        
        // Si no hay stock, crear uno inicial
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

        // Calcular disponibilidad por precio usando el nuevo sistema
        $precios = [
            50 => 1,
            60 => 1.5, 
            70 => 2,
            80 => 2.5,
            90 => 3
        ];

        $platosPorPrecio = [];
        foreach ($precios as $precio => $cantidad) {
            $platosPorPrecio[$precio] = $costillasDisponibles >= floatval($cantidad) ? 1 : 0;
        }
        
        // Obtener pedidos online pendientes
        $pedidosPendientes = DB::table('pedidos_online')
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->count();
        
        // Obtener ventas del día
        $ventasHoy = DB::table('ventas_costillas')
            ->whereDate('created_at', today())
            ->count();

        return view('cashier.dashboard', compact('costillasDisponibles', 'platosPorPrecio', 'pedidosPendientes', 'ventasHoy'));
    }

    public function createSale()
    {
        // Cálculo de stock de chancho en kg
        $chanchoId = (int) \App\Models\Setting::get('producto_id_chancho', 0);
        $stockChanchoKg = $chanchoId
            ? \DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'entrada')->sum('cantidad')
              - \DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'salida')->sum('cantidad')
            : 0;

        $pesoCostillaKg = (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2); 
        $stockKilos = $stockChanchoKg ?? 0;
        $costillasDisponibles = $pesoCostillaKg > 0 ? floor($stockKilos / $pesoCostillaKg) : 0;

        $platosPorCostillaMap = [50 => 16, 60 => 10, 70 => 8, 80 => 6, 90 => 4];
        $factorCostillas = ($pesoCostillaKg > 0) ? ($stockKilos / $pesoCostillaKg) : 0;
        $platosPorPrecio = [];
        foreach ($platosPorCostillaMap as $precio => $platosPorCostilla) {
            $platosPorPrecio[$precio] = (int) floor($factorCostillas * $platosPorCostilla);
        }
        $hayPlatos = array_sum($platosPorPrecio) > 0;
        $costillasDisponibles = $hayPlatos ? max(1, (int) floor($factorCostillas)) : 0;
        
        // Stock de pollo
        $polloId = (int) \App\Models\Setting::get('producto_id_pollo', 0);
        $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 2));
        $stockPolloUn = $polloId
            ? \DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'entrada')->sum('cantidad')
              - \DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'salida')->sum('cantidad')
            : 0;
        $porcionesPolloPosibles = $porcionesPorPollo > 0 ? floor($stockPolloUn * $porcionesPorPollo) : 0;
        
        // Verificar si hay stock crítico (menos de 3 platos en cualquier precio)
        $alertaStock = [];
        foreach ($platosPorPrecio as $precio => $cantidad) {
            if ($cantidad > 0 && $cantidad <= 3) {
                $alertaStock[] = "Quedan solo {$cantidad} platos de Bs. {$precio}";
            }
        }
        
        return view('cashier.sales.create', compact(
            'alertaStock', 'hayPlatos', 'platosPorPrecio', 'costillasDisponibles', 
            'stockKilos', 'pesoCostillaKg', 'stockPolloUn', 'porcionesPorPollo', 
            'porcionesPolloPosibles'
        ));
    }

    public function salesIndex()
    {
        return view('cashier.sales.index');
    }

    public function stockIndex()
    {
        return view('cashier.stock');
    }

    public function ordersIndex()
    {
        // Pedidos online de la base de datos
        $pedidosOnline = DB::table('pedidos')
            ->select('id', 'cliente_nombre', 'cliente_telefono', 'items_json', 'monto', 'estado_pago', 'creado_en', 'estado')
            ->orderBy('creado_en','desc')
            ->limit(50)
            ->get()
            ->map(function($p) {
                return (object)[
                    'id' => $p->id,
                    'tipo' => 'online',
                    'cliente_nombre' => $p->cliente_nombre ?? 'Cliente Online',
                    'cliente_telefono' => $p->cliente_telefono ?? '',
                    'items_json' => $p->items_json,
                    'monto' => $p->monto ?? 0,
                    'estado_pago' => $p->estado_pago ?? 'pendiente',
                    'creado_en' => $p->creado_en,
                    'estado' => $p->estado ?? 'pendiente'
                ];
            });
        
        // Ventas directas de la sesión
        $salesReceipts = session('sales_receipts', []);
        $ventasDirectas = collect($salesReceipts)->map(function($venta, $index) {
            $items = [];
            foreach($venta['items'] ?? [] as $item) {
                // Determinar tipo basado en descripción
                $tipo = 'chancho';
                if (stripos($item['descripcion'] ?? '', 'pollo') !== false) {
                    $tipo = 'pollo';
                }
                $items[] = [
                    'tipo' => $tipo,
                    'precio' => $item['precio'] ?? 0,
                    'cantidad' => $item['cantidad'] ?? 1
                ];
            }
            
            return (object)[
                'id' => 'venta_' . ($venta['ticket'] ?? 'V' . ($index + 1)),
                'tipo' => 'directa',
                'cliente_nombre' => $venta['cliente'] ?? 'Venta Directa',
                'cliente_telefono' => '',
                'items_json' => json_encode($items),
                'monto' => $venta['total'] ?? 0,
                'estado_pago' => 'pagado',
                'creado_en' => $venta['fecha'] ?? now()->format('Y-m-d H:i:s'),
                'estado' => 'completado'
            ];
        });
        
        // Combinar y ordenar por fecha
        $pedidos = $pedidosOnline->concat($ventasDirectas)
            ->sortByDesc('creado_en')
            ->take(100)
            ->values();
        
        // Debug info (remover en producción)
        $debugInfo = [
            'pedidos_online' => $pedidosOnline->count(),
            'ventas_directas' => $ventasDirectas->count(),
            'total_pedidos' => $pedidos->count(),
            'session_receipts' => count($salesReceipts)
        ];
        
        return view('cashier.orders.index', compact('pedidos', 'debugInfo'));
    }

    public function reprint(Request $request)
    {
        $ticket = $request->query('ticket');
        $log = session('sales_receipts', []);

        if ($ticket) {
            foreach ($log as $entry) {
                if (($entry['ticket'] ?? '') === $ticket) {
                    $binary = base64_decode($entry['pdf'] ?? '');
                    $name = $ticket . '.pdf';
                    return response($binary, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="' . $name . '"',
                    ]);
                }
            }
            return redirect()->route('cashier.reprint')->with('error', 'Comprobante no encontrado.');
        }

        return view('cashier.reprint', ['receipts' => array_reverse($log)]);
    }

    // 📥 Venta rápida (botones)
    public function sell(Request $request)
    {
        $request->validate([
            'tipo' => ['required','in:chancho,pollo'],
            'precio' => ['required','numeric','min:1'],
        ]);

        $tipo = $request->input('tipo');
        $precio = (float)$request->precio;
        $stockController = new \App\Http\Controllers\StockController();

        if ($tipo === 'chancho') {
            $platosPorCostilla = $this->mapaPlatos[$precio] ?? 1;
            $costillaConsumida = 1 / $platosPorCostilla; // fracción de costilla

            $pesoCostillaKg = (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2);
            $kilosConsumidos = $pesoCostillaKg * $costillaConsumida;

            $stockInfo = $stockController->getChanchoStockInfo();
            $factorCostillas = ($pesoCostillaKg > 0) ? ($stockInfo['stock_actual'] / $pesoCostillaKg) : 0;
            $platosDisponibles = (int) floor($factorCostillas * $platosPorCostilla);
            
            if ($platosDisponibles <= 0) {
                return back()->with('error', "Stock insuficiente. Quedan {$platosDisponibles} platos de Bs. {$precio}");
            }

            DB::table('stock')->insert([
                'producto_id' => $stockInfo['producto_id'],
                'cantidad' => $kilosConsumidos,
                'unidad' => 'kilos',
                'costo_unitario' => (float) (\App\Models\Setting::get('cpp_kilo_chancho', \App\Models\Setting::get('precio_kilo_chancho', 45))),
                'tipo' => 'salida',
                'fecha' => now(),
            ]);

            $items = [[
                'descripcion' => 'Chancho ' . number_format($precio,0) . ' Bs',
                'cantidad' => 1,
                'precio' => $precio,
                'subtotal' => $precio,
            ]];
            $total = $precio;
        } else {
            // Pollo por porción
            $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 2));
            $unidadesConsumidas = 1.0 / max(1, $porcionesPorPollo);

            $stockInfo = $stockController->getPolloStockInfo();
            $porcionesPolloPosibles = $porcionesPorPollo > 0 ? floor($stockInfo['stock_actual'] * $porcionesPorPollo) : 0;
            
            if ($porcionesPolloPosibles <= 0) {
                return back()->with('error', "Stock insuficiente. Quedan {$porcionesPolloPosibles} porciones de pollo");
            }

            DB::table('stock')->insert([
                'producto_id' => $stockInfo['producto_id'],
                'cantidad' => $unidadesConsumidas,
                'unidad' => 'unidades',
                'costo_unitario' => (float) (\App\Models\Setting::get('precio_kilo_pollo', 55)),
                'tipo' => 'salida',
                'fecha' => now(),
            ]);

            $items = [[
                'descripcion' => 'Pollo ' . number_format($precio,0) . ' Bs',
                'cantidad' => 1,
                'precio' => $precio,
                'subtotal' => $precio,
            ]];
            $total = $precio;
        }

        $ticket = strtoupper(uniqid('RC-'));
        $pdf = Pdf::loadView('receipts.invoice', [
            'ticket' => $ticket,
            'fecha' => now()->format('d/m/Y H:i'),
            'cajero' => auth()->user()->nombre ?? 'Cajero',
            'items' => $items,
            'total' => $total,
            'pago' => $total,
            'cambio' => 0,
            'metodo' => 'Efectivo',
            'cliente' => null,
            'cliente_telefono' => null,
        ])->setPaper('a4');

        $pdfBinary = base64_encode($pdf->output());
        session(['last_receipt_pdf' => $pdfBinary, 'last_receipt_name' => $ticket . '.pdf']);
        $salesLog = session('sales_receipts', []);
        $salesLog[] = [
            'ticket' => $ticket,
            'fecha' => now()->format('Y-m-d H:i:s'),
            'items' => $items,
            'total' => $total,
            'pdf' => $pdfBinary,
            'metodo' => 'Efectivo',
            'cliente' => null,
            'platos' => array_sum(array_map(function($it){ return (int)($it['cantidad'] ?? 1); }, $items)),
        ];
        session(['sales_receipts' => $salesLog]);
        // Enviar a cola de pantalla
        $apellido = trim((string)$request->input('cliente_apellido', ''));
        $referencia = trim((string)$request->input('cliente_direccion', ''));
        $observacion = trim((string)$request->input('observaciones', ''));
        $resumen = implode(', ', array_map(function($it){
            return ($it['cantidad'] ?? 1) . 'x ' . ($it['descripcion'] ?? 'Item');
        }, $items));
        DB::table('display_queue')->insert([
            'apellido' => $apellido ?: null,
            'referencia' => $referencia ?: null,
            'detalle' => $resumen,
            'total' => $total,
            'estado' => 'pendiente',
            'observacion' => $observacion ?: null,
            'creado_en' => now(),
        ]);
        return redirect()->route('cashier.reprint');
    }

    // Pantalla (Display)
    public function displayScreen()
    {
        $items = DB::table('display_queue')->where('estado','pendiente')->orderBy('creado_en','desc')->limit(20)->get();
        if ($items->count() === 0) {
            // Fallback: poblar con últimos comprobantes de la sesión si la cola está vacía
            $log = session('sales_receipts', []);
            $slice = array_slice(array_reverse($log), 0, 6);
            foreach ($slice as $entry) {
                $detalle = implode(', ', array_map(function($it){
                    return ($it['cantidad'] ?? 1) . 'x ' . ($it['descripcion'] ?? 'Item');
                }, $entry['items'] ?? []));
                DB::table('display_queue')->insert([
                    'apellido' => null,
                    'referencia' => null,
                    'detalle' => $detalle,
                    'total' => (float)($entry['total'] ?? 0),
                    'estado' => 'pendiente',
                    'creado_en' => now(),
                ]);
            }
            $items = DB::table('display_queue')->where('estado','pendiente')->orderBy('creado_en','desc')->limit(20)->get();
        }
        return view('cashier.display.index', ['display' => $items]);
    }

    public function displayClear(Request $request)
    {
        // Limpiar ventas directas
        DB::table('display_queue')->update(['estado' => 'cerrado']);
        
        // Marcar pedidos online como completados (solo si existen)
        $pedidosCount = DB::table('pedidos')
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->count();
            
        if ($pedidosCount > 0) {
            DB::table('pedidos')
                ->whereIn('estado', ['pendiente', 'preparando'])
                ->update(['estado' => 'entregado']);
        }
            
        return back()->with('status', 'Pantalla limpiada: todas las órdenes han sido cerradas');
    }

    public function displayFeed()
    {
        // Obtener items de la cola de display (ventas directas)
        $displayItems = DB::table('display_queue')
            ->whereIn('estado', ['pendiente','mostrado'])
            ->orderBy('creado_en','desc')
            ->limit(20)
            ->get()
            ->map(function($item) {
                $item->tipo = 'venta';
                $item->origen = 'Venta Directa';
                return $item;
            });

        // Obtener pedidos en línea activos
        $pedidosOnline = DB::table('pedidos')
            ->whereIn('estado', ['pendiente', 'preparando'])
            ->orderBy('creado_en','desc')
            ->limit(20)
            ->get()
            ->map(function($pedido) {
                // Convertir formato de pedido a formato de display
                $items = json_decode($pedido->items ?? '[]', true);
                $detalle = collect($items)->map(function($item) {
                    $nombre = $item['nombre'] ?? 'Item';
                    $precio = $item['precio'] ?? 0;
                    $cantidad = $item['cantidad'] ?? 1;
                    
                    // Determinar si es chancho o pollo basado en el precio
                    $tipo = '';
                    if (in_array($precio, [50, 60, 70, 80, 90])) {
                        $tipo = $precio == 60 ? 'Pollo' : 'Chancho';
                    } else {
                        // Detectar por nombre si no se puede por precio
                        if (stripos($nombre, 'pollo') !== false) {
                            $tipo = 'Pollo';
                        } elseif (stripos($nombre, 'chancho') !== false || stripos($nombre, 'costilla') !== false) {
                            $tipo = 'Chancho';
                        } else {
                            $tipo = $nombre;
                        }
                    }
                    
                    return $cantidad . 'x ' . $tipo . ' Bs.' . $precio;
                })->join(', ');
                
                return (object)[
                    'id' => 'pedido_' . $pedido->id,
                    'apellido' => $pedido->nombre ?? 'Cliente Online',
                    'referencia' => ($pedido->telefono ?? '') . ' - Pedido #' . $pedido->id,
                    'detalle' => $detalle,
                    'total' => $pedido->monto ?? 0,
                    'estado' => $pedido->estado,
                    'observacion' => $pedido->observaciones ?? null,
                    'creado_en' => $pedido->creado_en,
                    'tipo' => 'pedido',
                    'origen' => 'Pedido Online',
                    'pedido_id' => $pedido->id,
                    'estado_pago' => $pedido->estado_pago ?? 'pendiente'
                ];
            });

        // Combinar y ordenar por fecha
        $allItems = $displayItems->concat($pedidosOnline)
            ->sortByDesc('creado_en')
            ->take(30)
            ->values();

        return response()->json($allItems);
    }

    public function displayUpdate(Request $request, int $id)
    {
        $estado = $request->input('estado');
        if (!in_array($estado, ['pendiente','mostrado','cerrado'])) {
            return response()->json(['ok' => false], 422);
        }
        
        // Verificar si es un pedido online o venta directa
        if (str_starts_with($id, 'pedido_')) {
            $pedidoId = str_replace('pedido_', '', $id);
            // Actualizar estado del pedido online
            $nuevoEstado = $estado === 'cerrado' ? 'entregado' : ($estado === 'mostrado' ? 'preparando' : 'pendiente');
            DB::table('pedidos')->where('id', $pedidoId)->update(['estado' => $nuevoEstado]);
        } else {
            // Actualizar estado en display_queue para ventas directas
            DB::table('display_queue')->where('id', $id)->update(['estado' => $estado]);
        }
        
        return response()->json(['ok' => true]);
    }

    // 📥 Checkout con carrito
    public function checkoutSale(Request $request)
    {
        $data = $request->validate([
            'items_json' => ['required','string'],
            'metodo_pago' => ['required','string','max:50'],
            'monto_pagado' => ['nullable','numeric','min:0'],
            'cliente_nombre' => ['nullable','string','max:120'],
            'cliente_telefono' => ['nullable','string','max:60'],
            'observaciones' => ['nullable','string','max:500'],
        ]);

        $items = json_decode($data['items_json'], true) ?: [];
        if (count($items) === 0) {
            return back()->with('error', 'Agrega al menos un producto.');
        }

        $stockController = new \App\Http\Controllers\StockController();
        $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 2));
        $pesoCostillaKg = (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2);

        $movimientos = [];
        $total = 0;

        foreach ($items as $item) {
            $tipo = $item['tipo'];
            $cantidad = (float)($item['cantidad'] ?? 1);
            $precio = (float)($item['precio'] ?? 0);
            $subtotal = $precio * $cantidad;
            $total += $subtotal;

            if ($tipo === 'chancho') {
                $platosPorCostilla = $this->mapaPlatos[$precio] ?? 1;
                $costillaConsumida = 1 / $platosPorCostilla;
                $kilos = $pesoCostillaKg * $costillaConsumida * $cantidad;

                $stockInfo = $stockController->getChanchoStockInfo();
                $factorCostillas = ($pesoCostillaKg > 0) ? ($stockInfo['stock_actual'] / $pesoCostillaKg) : 0;
                $platosDisponibles = (int) floor($factorCostillas * $platosPorCostilla);
                
                if ($platosDisponibles < $cantidad) {
                    return back()->with('error', "Stock insuficiente. Solo quedan {$platosDisponibles} platos de Bs. {$precio}");
                }

                $movimientos[] = ['producto_id'=>$stockInfo['producto_id'],'cantidad'=>$kilos,'unidad'=>'kilos'];
            } else {
                $unidades = ($cantidad / max(1, $porcionesPorPollo));
                $stockInfo = $stockController->getPolloStockInfo();
                $porcionesPolloPosibles = $porcionesPorPollo > 0 ? floor($stockInfo['stock_actual'] * $porcionesPorPollo) : 0;
                
                if ($porcionesPolloPosibles < $cantidad) {
                    return back()->with('error', "Stock insuficiente. Solo quedan {$porcionesPolloPosibles} porciones de pollo");
                }
                $movimientos[] = ['producto_id'=>$stockInfo['producto_id'],'cantidad'=>$unidades,'unidad'=>'unidades'];
            }
        }

        DB::transaction(function () use ($movimientos) {
            foreach ($movimientos as $m) {
                DB::table('stock')->insert([
                    'producto_id' => $m['producto_id'],
                    'cantidad' => $m['cantidad'],
                    'unidad' => $m['unidad'],
                    'costo_unitario' => ($m['unidad']==='kilos')
                        ? (float)(\App\Models\Setting::get('cpp_kilo_chancho', \App\Models\Setting::get('precio_kilo_chancho', 45)))
                        : (float)(\App\Models\Setting::get('precio_kilo_pollo', 55)),
                    'tipo' => 'salida',
                    'fecha' => now(),
                ]);
            }
        });

        $pago = (float)($data['monto_pagado'] ?? 0);
        $cambio = max(0, $pago - $total);
        $ticket = strtoupper(uniqid('RC-'));

        $pdf = Pdf::loadView('receipts.invoice', [
            'ticket' => $ticket,
            'fecha' => now()->format('d/m/Y H:i'),
            'cajero' => auth()->user()->nombre ?? 'Cajero',
            'items' => array_map(function ($item) use ($data){
                $desc = ($item['tipo']==='chancho' ? ('Chancho '.number_format($item['precio'],0).' Bs') : ('Pollo '.number_format($item['precio'],0).' Bs'));
                if (!empty($item['porcion'])) { $desc .= ' — '.$item['porcion']; }
                return [
                    'descripcion' => $desc,
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subtotal'] ?? ($item['precio']*$item['cantidad']),
                ];
            }, $items),
            'total' => $total,
            'pago' => $pago,
            'cambio' => $cambio,
            'metodo' => $data['metodo_pago'],
            'cliente' => $data['cliente_nombre'] ?? null,
            'cliente_telefono' => $data['cliente_telefono'] ?? null,
            'observaciones' => $data['observaciones'] ?? null,
        ])->setPaper('a4');

        $pdfBinary = base64_encode($pdf->output());
        session(['last_receipt_pdf' => $pdfBinary, 'last_receipt_name' => $ticket . '.pdf']);
        $salesLog = session('sales_receipts', []);
        $salesLog[] = [
            'ticket' => $ticket,
            'fecha' => now()->format('Y-m-d H:i:s'),
            'items' => array_map(function ($item) use ($data){
                $desc = ($item['tipo']==='chancho' ? ('Chancho '.number_format($item['precio'],0).' Bs') : ('Pollo '.number_format($item['precio'],0).' Bs'));
                if (!empty($item['porcion'])) { $desc .= ' — '.$item['porcion']; }
                else if (!empty($data['observaciones'])) { $desc .= ' — '.$data['observaciones']; }
                return [
                    'descripcion' => $desc,
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subtotal'] ?? ($item['precio']*$item['cantidad']),
                ];
            }, $items),
            'total' => $total,
            'pdf' => $pdfBinary,
            'metodo' => $data['metodo_pago'] ?? null,
            'cliente' => $data['cliente_nombre'] ?? null,
            'platos' => array_sum(array_map(function($it){ return (int)($it['cantidad'] ?? 1); }, $items)),
        ];
        session(['sales_receipts' => $salesLog]);
        // Enviar a Pantalla con el mismo formato que reimprimir
        $detalle = implode(', ', array_map(function ($item) use ($data) {
            $desc = ($item['tipo']==='chancho' ? ('Chancho '.number_format($item['precio'],0).' Bs') : ('Pollo '.number_format($item['precio'],0).' Bs'));
            if (!empty($item['porcion'])) { $desc .= ' — '.$item['porcion']; }
            else if (!empty($data['observaciones'])) { $desc .= ' — '.$data['observaciones']; }
            return $desc.' x'.$item['cantidad'];
        }, $items));
        DB::table('display_queue')->insert([
            'apellido' => $data['cliente_nombre'] ?? null,
            'referencia' => trim(($request->input('cliente_direccion','') ?: '').' - '.$ticket, ' -'),
            'detalle' => $detalle,
            'total' => $total,
            'estado' => 'pendiente',
            'observacion' => $data['observaciones'] ?? null,
            'creado_en' => now(),
        ]);
        return redirect()->route('cashier.reprint');
    }
}

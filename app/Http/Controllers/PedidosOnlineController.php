<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidosOnlineController extends Controller
{
    public function index()
    {
        $pedidosOnline = DB::table('pedidos_online')
            ->where('cliente_nombre', '!=', 'Venta Directa - Cajero')
            ->orderBy('created_at', 'desc')
            ->get();
            
        $ventasDirectas = DB::table('pedidos_online')
            ->where('cliente_nombre', 'Venta Directa - Cajero')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('cashier.pedidos.index', compact('pedidosOnline', 'ventasDirectas'));
    }

    public function crear(Request $request)
    {
        $request->validate([
            'cliente_nombre' => 'required|string|max:255',
            'cliente_telefono' => 'required|string|max:20',
            'items' => 'required|array',
            'total' => 'required|numeric|min:0'
        ]);

        // Generar número de ticket único
        $numeroTicket = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        
        // Verificar que no exista
        while (DB::table('pedidos_online')->where('numero_ticket', $numeroTicket)->exists()) {
            $numeroTicket = 'T' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        DB::table('pedidos_online')->insert([
            'numero_ticket' => $numeroTicket,
            'cliente_nombre' => $request->cliente_nombre,
            'cliente_telefono' => $request->cliente_telefono,
            'items' => json_encode($request->items),
            'total' => $request->total,
            'estado' => 'pendiente',
            'fecha_pedido' => now(),
            'notas' => $request->notas,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'numero_ticket' => $numeroTicket,
            'message' => 'Pedido creado exitosamente'
        ]);
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate([
            'estado' => 'required|in:pendiente,preparando,listo,entregado,cancelado'
        ]);

        $updated = DB::table('pedidos_online')
            ->where('id', $id)
            ->update([
                'estado' => $request->estado,
                'fecha_entrega' => $request->estado === 'entregado' ? now() : null,
                'updated_at' => now()
            ]);

        if ($updated) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 400);
    }

    public function pantallaPedidos()
    {
        $pedidos = DB::table('pedidos_online')
            ->whereIn('estado', ['pendiente', 'preparando', 'listo'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('public.pantalla-pedidos', compact('pedidos'));
    }
}
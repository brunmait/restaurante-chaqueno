<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Compra;
use App\Models\CompraItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index()
    {
        $compras = Compra::with('proveedor', 'items')->orderBy('fecha_compra', 'desc')->get();
        $proveedores = Proveedor::where('activo', true)->get();
        
        // Verificar stock bajo
        $stockActual = DB::table('stock_costillas')->first();
        $alertaStock = $stockActual && $stockActual->costillas_disponibles <= $stockActual->stock_minimo;
        
        return view('admin.compras.index', compact('compras', 'proveedores', 'alertaStock', 'stockActual'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proveedor_id' => 'required|exists:proveedores,id',
            'fecha_compra' => 'required|date',
            'cantidad_costillares' => 'required|integer|min:1',
            'precio_por_costillar' => 'required|numeric|min:0',
            'observaciones' => 'nullable|string'
        ]);

        DB::transaction(function () use ($request) {
            // Calcular totales
            $cantidadCostillares = $request->cantidad_costillares;
            $precioPorCostillar = $request->precio_por_costillar;
            $costillasTotales = $cantidadCostillares * 18; // 18 costillas por costillar
            $costoPorCostilla = $precioPorCostillar / 18;
            $total = $cantidadCostillares * $precioPorCostillar;

            // Crear compra
            $compra = Compra::create([
                'proveedor_id' => $request->proveedor_id,
                'fecha_compra' => $request->fecha_compra,
                'total' => $total,
                'observaciones' => $request->observaciones
            ]);

            // Crear item de compra
            CompraItem::create([
                'compra_id' => $compra->id,
                'producto' => 'costillar_entero',
                'cantidad' => $cantidadCostillares,
                'precio_unitario' => $precioPorCostillar,
                'costillas_totales' => $costillasTotales,
                'costo_por_costilla' => $costoPorCostilla
            ]);

            // Actualizar stock
            $stockActual = DB::table('stock_costillas')->first();
            
            if ($stockActual) {
                // Calcular nuevo costo promedio ponderado
                $stockAnterior = $stockActual->costillas_disponibles;
                $costoAnterior = $stockActual->costo_promedio;
                $nuevoStock = $stockAnterior + $costillasTotales;
                
                $nuevoCostoPromedio = $nuevoStock > 0 
                    ? (($stockAnterior * $costoAnterior) + ($costillasTotales * $costoPorCostilla)) / $nuevoStock
                    : $costoPorCostilla;

                DB::table('stock_costillas')
                    ->where('id', $stockActual->id)
                    ->update([
                        'costillas_disponibles' => $nuevoStock,
                        'costo_promedio' => round($nuevoCostoPromedio, 2),
                        'updated_at' => now()
                    ]);
            } else {
                // Crear registro inicial
                DB::table('stock_costillas')->insert([
                    'costillas_disponibles' => $costillasTotales,
                    'costo_promedio' => $costoPorCostilla,
                    'stock_minimo' => 10,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        });

        return redirect()->back()->with('success', 'Compra registrada exitosamente. Stock actualizado.');
    }

    public function proveedores()
    {
        $proveedores = Proveedor::all();
        return view('admin.compras.proveedores', compact('proveedores'));
    }

    public function storeProveedor(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255'
        ]);

        Proveedor::create($request->all());

        return redirect()->back()->with('success', 'Proveedor registrado exitosamente');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    public function reset(Request $request)
    {
        \DB::table('stock')->delete();
        \DB::table('productos')->update(['stock' => 0]);
        return back()->with('status', 'Inventario vaciado: movimientos borrados y stock en 0');
    }

    public function getChanchoProductId(): int
    {
        $cached = (int) (Setting::get('producto_id_chancho', 0));
        if ($cached > 0) {
            $p = DB::table('productos')->where('id',$cached)->first();
            if ($p && (stripos($p->nombre,'chancho') !== false || stripos($p->descripcion ?? '', 'chancho') !== false)) {
                return $cached;
            }
        }

        $producto = DB::table('productos')
            ->where('nombre', 'Carne de chancho')
            ->orWhere('nombre', 'like', '%chancho%')
            ->first();

        if (!$producto) {
            $id = DB::table('productos')->insertGetId([
                'nombre' => 'Carne de chancho',
                'descripcion' => 'Materia prima para platos de chancho (se compra por kilos)',
                'precio' => 45.00,
                'categoria' => 'insumo',
                'stock' => 0,
                'imagen' => '',
            ]);
        } else {
            $id = (int) $producto->id;
        }

        Setting::set('producto_id_chancho', (string)$id);
        return $id;
    }

    public function getPolloProductId(): int
    {
        $cached = (int) (Setting::get('producto_id_pollo', 0));
        if ($cached > 0) {
            $p = DB::table('productos')->where('id',$cached)->first();
            if ($p && (stripos($p->nombre,'pollo') !== false || stripos($p->descripcion ?? '', 'pollo') !== false)) {
                return $cached;
            }
        }

        $producto = DB::table('productos')
            ->where('nombre', 'Pollo entero')
            ->orWhere('nombre', 'like', '%pollo%')
            ->first();

        if (!$producto) {
            $id = DB::table('productos')->insertGetId([
                'nombre' => 'Pollo entero',
                'descripcion' => 'Materia prima para platos de pollo (se compra por unidad)',
                'precio' => 55.00,
                'categoria' => 'insumo',
                'stock' => 0,
                'imagen' => '',
            ]);
        } else {
            $id = (int) $producto->id;
        }

        Setting::set('producto_id_pollo', (string)$id);
        return $id;
    }

    public function index()
    {
        $productoId = (int) $this->getChanchoProductId();
        $kilos = DB::table('stock')->where('producto_id', $productoId)->sum('cantidad');
        $minKilos = (float) (Setting::get('stock_min_kilos', 5));
        $alerta = $kilos <= $minKilos;
        $productos = DB::table('productos')->select('id', 'nombre', 'categoria', 'stock', 'precio')->orderBy('nombre')->get();
        return view('admin.stock', compact('kilos', 'minKilos', 'alerta', 'productos', 'productoId'));
    }

    public function entrada(Request $request)
    {
        $request->validate([
            'kilos' => ['nullable', 'numeric', 'min:0.1'],
            'costillas' => ['nullable', 'integer', 'min:1'],
            'unidades' => ['nullable', 'integer', 'min:1'],
            'precio_kilo' => ['nullable', 'numeric', 'min:0'],
            'producto_id' => ['required', 'integer', 'min:1'],
        ]);

        $productoId = $request->integer('producto_id');
        $producto = DB::table('productos')->where('id', $productoId)->first();
        
        if (!$producto) {
            return back()->with('error', 'Producto no encontrado');
        }

        if ($productoId === $this->getChanchoProductId()) {
            $unidad = 'kilos';
            if ($request->filled('costillas') && (int)$request->costillas > 0) {
                $pesoCostilla = (float) Setting::get('peso_costilla_prom_kg', 6.0); // 🔥 por defecto 6 kg
                $cantidad = ((int)$request->costillas) * max(0.01, $pesoCostilla);
            } else {
                $cantidad = $request->kilos;
            }
        } elseif ($productoId === $this->getPolloProductId()) {
            $cantidad = $request->unidades;
            $unidad = 'unidades';
        } elseif ($producto->categoria === 'refresco') {
            $cantidad = $request->unidades;
            $unidad = 'unidades';
        } elseif ($producto->categoria === 'insumo') {
            $cantidad = $request->kilos;
            $unidad = 'kilos';
        } else {
            return back()->with('error', 'Los platos se calculan automáticamente según los insumos');
        }

        if (!$cantidad || $cantidad <= 0) {
            return back()->with('error', 'Cantidad inválida');
        }

        DB::table('stock')->insert([
            'producto_id' => $productoId,
            'cantidad' => $cantidad,
            'unidad' => $unidad,
            'costo_unitario' => $request->filled('precio_kilo') ? (float)$request->precio_kilo : null,
            'tipo' => 'entrada',
            'fecha' => now(),
        ]);

        $updateData = [
            'stock' => DB::raw('(COALESCE(stock,0) + ' . ((float)$cantidad) . ')')
        ];

        if ($request->filled('precio_kilo') && $request->precio_kilo > 0) {
            $updateData['precio'] = $request->precio_kilo;
        }

        DB::table('productos')->where('id', $productoId)->update($updateData);

        if ($request->filled('precio_kilo') && $request->precio_kilo > 0) {
            if ($productoId === $this->getChanchoProductId()) {
                Setting::set('precio_kilo_chancho', (string)$request->precio_kilo);
                $productoRow = DB::table('productos')->where('id', $productoId)->first();
                $existKgAnterior = max(0.0, (float)($productoRow->stock ?? 0) - (float)$cantidad);
                $cppAnterior = (float) Setting::get('cpp_kilo_chancho', $request->precio_kilo);
                $valorExistNuevo = ($existKgAnterior * $cppAnterior) + ((float)$cantidad * (float)$request->precio_kilo);
                $existKgNuevo = max(0.000001, $existKgAnterior + (float)$cantidad);
                $cppNuevo = $valorExistNuevo / $existKgNuevo;
                Setting::set('cpp_kilo_chancho', (string) round($cppNuevo, 4));
            } elseif ($productoId === $this->getPolloProductId()) {
                Setting::set('precio_kilo_pollo', (string)$request->precio_kilo);
            }
        }

        return back()->with('status', "Entrada registrada: {$cantidad} {$unidad}");
    }

    public function configurar(Request $request)
    {
        $request->validate([
            'gramos_costilla' => ['required', 'integer', 'min:50'],   // gramos por plato
            'peso_costilla_prom_kg' => ['nullable', 'numeric', 'min:0.1'], // peso costilla
            'stock_min_kilos' => ['required', 'numeric', 'min:0'],
            'producto_id_chancho' => ['nullable', 'integer', 'min:1'],
            'porciones_por_pollo' => ['nullable', 'integer', 'min:1'],
            'stock_min_pollos' => ['nullable', 'integer', 'min:0'],
            'producto_id_pollo' => ['nullable', 'integer', 'min:1'],
        ]);

        Setting::set('gramos_costilla', (string)$request->gramos_costilla);
        Setting::set('peso_costilla_prom_kg', (string)($request->peso_costilla_prom_kg ?? 6.0));
        Setting::set('stock_min_kilos', (string)$request->stock_min_kilos);

        if ($request->filled('producto_id_chancho')) {
            Setting::set('producto_id_chancho', (string)$request->producto_id_chancho);
        }
        if ($request->filled('porciones_por_pollo')) {
            Setting::set('porciones_por_pollo', (string)$request->porciones_por_pollo);
        }
        if ($request->filled('stock_min_pollos')) {
            Setting::set('stock_min_pollos', (string)$request->stock_min_pollos);
        }
        if ($request->filled('producto_id_pollo')) {
            Setting::set('producto_id_pollo', (string)$request->producto_id_pollo);
        }

        return back()->with('status', 'Configuración actualizada');
    }

    public function getStockInfo(): array
    {
        return $this->getChanchoStockInfo();
    }

    public function getChanchoStockInfo(): array
    {
        $productoId = $this->getChanchoProductId();
        $entradas = DB::table('stock')->where('producto_id', $productoId)->where('tipo', 'entrada')->sum('cantidad');
        $salidas = DB::table('stock')->where('producto_id', $productoId)->where('tipo', 'salida')->sum('cantidad');
        $stockActual = $entradas - $salidas;
        return [
            'producto_id' => $productoId,
            'stock_actual' => max(0, $stockActual),
            'entradas' => $entradas,
            'salidas' => $salidas,
        ];
    }

    public function getPolloStockInfo(): array
    {
        $productoId = $this->getPolloProductId();
        $entradas = DB::table('stock')->where('producto_id', $productoId)->where('tipo', 'entrada')->sum('cantidad');
        $salidas = DB::table('stock')->where('producto_id', $productoId)->where('tipo', 'salida')->sum('cantidad');
        $stockActual = $entradas - $salidas;
        return [
            'producto_id' => $productoId,
            'stock_actual' => max(0, $stockActual),
            'entradas' => $entradas,
            'salidas' => $salidas,
        ];
    }

    public function overview()
    {
        $productos = DB::table('productos')->select('id','nombre','categoria','stock','precio')->orderBy('nombre')->get();
        return view('admin.inventory.overview', compact('productos'));
    }

    public function getGanancias($desde = null, $hasta = null)
    {
        $desde = $desde ?: now()->startOfDay();
        $hasta = $hasta ?: now()->endOfDay();

        $ganancias = [
            'chancho' => ['entradas' => 0,'salidas' => 0,'costo_total' => 0,'ventas_totales' => 0,'ganancia_neta' => 0],
            'pollo' => ['entradas' => 0,'salidas' => 0,'costo_total' => 0,'ventas_totales' => 0,'ganancia_neta' => 0],
            'refrescos' => ['entradas' => 0,'salidas' => 0,'costo_total' => 0,'ventas_totales' => 0,'ganancia_neta' => 0]
        ];

        // Chancho
        $chanchoId = $this->getChanchoProductId();
        $chanchoEntradas = DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'entrada')->whereBetween('fecha', [$desde, $hasta])->get();
        $chanchoSalidas = DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'salida')->whereBetween('fecha', [$desde, $hasta])->get();

        foreach($chanchoEntradas as $entrada) {
            $ganancias['chancho']['entradas'] += $entrada->cantidad;
            $ganancias['chancho']['costo_total'] += $entrada->cantidad * Setting::get('precio_kilo_chancho', 45);
        }

        foreach($chanchoSalidas as $salida) {
            $ganancias['chancho']['salidas'] += $salida->cantidad;
            $gramosPorPlato = (float) Setting::get('gramos_costilla', 750); // 🔥 750 g por defecto = 8 platos/costilla
            $platosVendidos = ($salida->cantidad * 1000) / $gramosPorPlato;
            $ganancias['chancho']['ventas_totales'] += $platosVendidos * 60;
        }

        $ganancias['chancho']['ganancia_neta'] = $ganancias['chancho']['ventas_totales'] - $ganancias['chancho']['costo_total'];

        // Pollo
        $polloId = $this->getPolloProductId();
        $polloEntradas = DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'entrada')->whereBetween('fecha', [$desde, $hasta])->get();
        $polloSalidas = DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'salida')->whereBetween('fecha', [$desde, $hasta])->get();

        foreach($polloEntradas as $entrada) {
            $ganancias['pollo']['entradas'] += $entrada->cantidad;
            $ganancias['pollo']['costo_total'] += $entrada->cantidad * Setting::get('precio_kilo_pollo', 55);
        }

        $porcionesPorPollo = (int) (Setting::get('porciones_por_pollo', 2));
        foreach($polloSalidas as $salida) {
            $ganancias['pollo']['salidas'] += $salida->cantidad;
            $porcionesVendidas = $salida->cantidad * max(1, $porcionesPorPollo);
            $ganancias['pollo']['ventas_totales'] += $porcionesVendidas * 60;
        }

        $ganancias['pollo']['ganancia_neta'] = $ganancias['pollo']['ventas_totales'] - $ganancias['pollo']['costo_total'];

        return $ganancias;
    }

    public function low()
    {
        $minChancho = (float) Setting::get('stock_min_kilos', 5);
        $minPollo = (float) Setting::get('stock_min_pollos', 5);
        $chancho = $this->getChanchoStockInfo();
        $pollo = $this->getPolloStockInfo();
        return view('admin.inventory.low', compact('chancho','pollo','minChancho','minPollo'));
    }

    public function movements()
    {
        $movs = DB::table('stock')->orderBy('fecha','desc')->limit(200)->get();
        return view('admin.inventory.movements', compact('movs'));
    }

    public function report()
    {
        $desde = request('desde');
        $hasta = request('hasta');
        $query = DB::table('stock');
        if ($desde) { $query->where('fecha','>=',$desde.' 00:00:00'); }
        if ($hasta) { $query->where('fecha','<=',$hasta.' 23:59:59'); }
        $movs = $query->orderBy('fecha','desc')->get();
        return view('admin.inventory.report', compact('movs','desde','hasta'));
    }
   
public function menu()
{
    $productos = \DB::table('productos')->get();
    return view('public.menu', compact('productos'));
}
}

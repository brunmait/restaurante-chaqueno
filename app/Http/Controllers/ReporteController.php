<?php

namespace App\Http\Controllers;

use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReporteController extends Controller
{
    public function generarReporteDiario()
    {
        $fecha = Carbon::today();
        
        // Verificar si ya existe reporte para hoy
        $reporteExistente = Reporte::where('fecha_reporte', $fecha)
                                  ->where('tipo', 'ventas_diario')
                                  ->first();
        
        if ($reporteExistente) {
            return;
        }

        // Obtener datos de ventas del día
        $ventasHoy = DB::table('ventas_costillas')
                      ->whereDate('created_at', $fecha)
                      ->get();

        $totalVentas = $ventasHoy->sum('total');
        $totalPedidos = $ventasHoy->count();
        $totalCostillas = $ventasHoy->sum('cantidad_costillas');

        $datos = [
            'total_costillas' => $totalCostillas,
            'observaciones' => "Reporte de ventas del día {$fecha->format('d/m/Y')}"
        ];

        Reporte::create([
            'tipo' => 'ventas_diario',
            'titulo' => "Ventas Diarias - {$fecha->format('d/m/Y')}",
            'datos' => $datos,
            'fecha_reporte' => $fecha,
            'total_ventas' => $totalVentas,
            'total_pedidos' => $totalPedidos,
            'usuario_id' => auth()->id() ?? 3
        ]);
    }

    public function generarReporteMensual()
    {
        $fechaInicio = Carbon::now()->startOfMonth();
        $fechaFin = Carbon::now()->endOfMonth();
        
        // Verificar si ya existe reporte para este mes
        $reporteExistente = Reporte::where('fecha_reporte', $fechaInicio)
                                  ->where('tipo', 'ventas_mensual')
                                  ->first();
        
        if ($reporteExistente) {
            return redirect()->back()->with('status', 'Ya existe un reporte mensual para este período.');
        }

        // Obtener datos de ventas del mes
        $ventasMes = DB::table('ventas_costillas')
                      ->whereBetween('created_at', [$fechaInicio, $fechaFin])
                      ->get();

        $totalVentas = $ventasMes->sum('total');
        $totalPedidos = $ventasMes->count();
        $totalCostillas = $ventasMes->sum('cantidad_costillas');

        $datos = [
            'total_costillas' => $totalCostillas,
            'observaciones' => "Reporte de ventas mensual de {$fechaInicio->format('F Y')}",
            'periodo' => [
                'inicio' => $fechaInicio->format('Y-m-d'),
                'fin' => $fechaFin->format('Y-m-d')
            ]
        ];

        Reporte::create([
            'tipo' => 'ventas_mensual',
            'titulo' => "Ventas Mensuales - {$fechaInicio->format('F Y')}",
            'datos' => $datos,
            'fecha_reporte' => $fechaInicio,
            'total_ventas' => $totalVentas,
            'total_pedidos' => $totalPedidos,
            'usuario_id' => auth()->id() ?? 3
        ]);
        
        return redirect()->back()->with('status', 'Reporte mensual de ventas generado correctamente.');
    }

    public function generarReporteFinDia()
    {
        $stockActual = DB::table('stock_costillas')->value('costillas_disponibles') ?? 0;
        
        if ($stockActual <= 0) {
            $fecha = Carbon::today();
            
            // Verificar si ya existe reporte de fin de día para hoy
            $reporteExistente = Reporte::where('fecha_reporte', $fecha)
                                      ->where('tipo', 'ventas_fin_dia')
                                      ->first();
            
            if (!$reporteExistente) {
                // Obtener ventas del día hasta el momento
                $ventasHoy = DB::table('ventas_costillas')
                              ->whereDate('created_at', $fecha)
                              ->get();

                $totalVentas = $ventasHoy->sum('total');
                $totalPedidos = $ventasHoy->count();
                $totalCostillas = $ventasHoy->sum('cantidad_costillas');

                $datos = [
                    'total_costillas' => $totalCostillas,
                    'observaciones' => "Reporte de ventas - Se agotó el stock del día {$fecha->format('d/m/Y')}"
                ];

                Reporte::create([
                    'tipo' => 'ventas_fin_dia',
                    'titulo' => "Ventas Finalizadas - {$fecha->format('d/m/Y')}",
                    'datos' => $datos,
                    'fecha_reporte' => $fecha,
                    'total_ventas' => $totalVentas,
                    'total_pedidos' => $totalPedidos,
                    'usuario_id' => auth()->id() ?? 3
                ]);
            }
        }
    }

    public function marcarComoLeido($id)
    {
        $reporte = Reporte::findOrFail($id);
        // Como no tenemos campo leido, podemos usar un campo en datos
        $datos = $reporte->datos ?? [];
        $datos['leido'] = true;
        $reporte->update(['datos' => $datos]);
        
        return response()->json(['success' => true]);
    }

    public function obtenerReportesNoLeidos()
    {
        return Reporte::whereIn('tipo', ['ventas_diario', 'ventas_mensual', 'ventas_fin_dia'])
                     ->whereRaw("JSON_EXTRACT(datos, '$.leido') IS NULL OR JSON_EXTRACT(datos, '$.leido') = false")
                     ->orderBy('created_at', 'desc')
                     ->get();
    }

    public function index()
    {
        $reportes = Reporte::orderBy('created_at', 'desc')
                          ->paginate(20);
        
        return view('admin.reportes.index', compact('reportes'));
    }
}
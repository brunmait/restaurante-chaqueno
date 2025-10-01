@extends('layouts.cajero')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="theme-accent"><i class="fa fa-list me-2"></i>Lista de Ventas</h3>
    <div>
        <a href="{{ route('cashier.sales.create') }}" class="btn btn-primary me-2">
            <i class="fa fa-plus me-1"></i>Nueva Venta
        </a>
        <a href="{{ route('cashier.dashboard') }}" class="btn btn-outline-light">Volver al Inicio</a>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<style>
    .theme-accent { color:#8b1e1e; }
    .border-accent { border-color:#8b1e1e !important; }
    .bg-accent-soft { background:#fff; }
    .card.border-danger { border-width:2px; border-color:#8b1e1e !important; }
    .shadow-sm { box-shadow:0 0.125rem 0.25rem rgba(0,0,0,.075); }
    .btn-primary { background-color:#b22222; border-color:#8b1e1e; }
    .btn-primary:hover { background-color:#8b1e1e; border-color:#6e1717; }
</style>

<div class="card bg-accent-soft border-danger shadow-sm">
    <div class="card-header bg-white border-accent">
        <h5 class="mb-0 theme-accent">
            <i class="fas fa-history me-2"></i>Historial de Ventas
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Importe</th>
                        <th>Stock Consumido</th>
                        <th>Cajero</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ventas = \DB::table('stock')
                            ->where('tipo', 'salida')
                            ->orderBy('fecha', 'desc')
                            ->limit(50)
                            ->get();
                        $pesoCostillaKg = (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2);
                        $platosPorCostillaMap = [50=>16,60=>10,70=>8,80=>6,90=>4];
                        $preciosOrdenadosDesc = [90,80,70,60,50];
                        $kilosPorPlato = [];
                        foreach($platosPorCostillaMap as $p=>$ppc){ $kilosPorPlato[$p] = $ppc>0 ? ($pesoCostillaKg/$ppc) : 0; }
                        $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 5));
                        $totalVendido = 0;

                        $grupos = [];
                        foreach($ventas as $v){ $key = \Carbon\Carbon::parse($v->fecha)->format('Y-m-d H:i:s'); $grupos[$key][] = $v; }
                    @endphp
                    @forelse($grupos as $fechaKey => $movs)
                        @php
                            $itemsGrupo = []; $importeGrupo = 0; $platosGrupo = 0;
                            foreach($movs as $venta){
                                $producto = \DB::table('productos')->where('id', $venta->producto_id)->first();
                                $precio = 0; $cantidadPlatos = 0; $importe = 0; $stockTxt = '';
                                if($producto && stripos($producto->nombre,'chancho') !== false) {
                                    $mejorDiff = INF; $mejorPrecio = 0; $mejorPlatos = 0; $epsilon = 0.000001;
                                    foreach($preciosOrdenadosDesc as $p){ $kpp = $kilosPorPlato[$p] ?? 0; if($kpp<=0) continue; $platosEstimados = max(1,(int) round($venta->cantidad/$kpp)); $diff = abs($venta->cantidad-($platosEstimados*$kpp)); if($diff+$epsilon<$mejorDiff){ $mejorDiff=$diff; $mejorPrecio=$p; $mejorPlatos=$platosEstimados; } }
                                    $precio = $mejorPrecio; $cantidadPlatos = $mejorPlatos; $importe = $cantidadPlatos*$precio; $stockTxt = number_format($venta->cantidad,2).' kg';
                                } elseif($producto && stripos($producto->nombre,'pollo') !== false) {
                                    $precio = 60; $cantidadPlatos = max(1,(int) round($venta->cantidad*max(1,$porcionesPorPollo))); $importe = $cantidadPlatos*$precio; $stockTxt = number_format($venta->cantidad,2).' unidades';
                                } else { $stockTxt = number_format($venta->cantidad,2); }
                                $itemsGrupo[] = ['producto'=>$producto->nombre ?? 'Producto','precio'=>$precio,'platos'=>$cantidadPlatos,'importe'=>$importe,'stock'=>$stockTxt];
                                $importeGrupo += $importe; $platosGrupo += $cantidadPlatos; $totalVendido += $importe;
                            }
                            $productoTitulo = count(array_unique(array_map(fn($i)=>$i['producto'],$itemsGrupo)))>1 ? 'Mixto' : ($itemsGrupo[0]['producto'] ?? 'Producto');
                            $idCollapse = 'det_'.md5($fechaKey);
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($fechaKey)->format('d/m/Y H:i') }}</td>
                            <td>{{ $productoTitulo }}</td>
                            <td>-</td>
                            <td>{{ $platosGrupo }} platos</td>
                            <td>Bs. {{ number_format($importeGrupo,2) }}</td>
                            <td>-</td>
                            <td>{{ auth()->user()->nombre ?? 'Cajero' }}</td>
                            <td>
                                <a href="#{{ $idCollapse }}" data-bs-toggle="collapse" class="btn btn-sm btn-outline-info" title="Detalles">
                                    <i class="fa fa-list"></i>
                                </a>
                                <a href="{{ route('cashier.reprint') }}" class="btn btn-sm btn-outline-primary" title="Reimprimir último">
                                    <i class="fa fa-print"></i>
                                </a>
                            </td>
                        </tr>
                        <tr class="collapse" id="{{ $idCollapse }}">
                            <td colspan="8" class="p-0">
                                <div class="p-2">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Precio</th>
                                                    <th>Platos/Porciones</th>
                                                    <th>Importe</th>
                                                    <th>Stock</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($itemsGrupo as $it)
                                                    <tr>
                                                        <td>{{ $it['producto'] }}</td>
                                                        <td>Bs. {{ $it['precio'] }}</td>
                                                        <td>{{ $it['platos'] }}</td>
                                                        <td>Bs. {{ number_format($it['importe'],2) }}</td>
                                                        <td>{{ $it['stock'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">No hay ventas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($ventas->count() > 0)
            <div class="mt-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">Mostrando las últimas {{ $ventas->count() }} ventas</small>
                <div class="badge bg-success fs-6">Total vendido: Bs. {{ number_format($totalVendido, 2) }}</div>
            </div>
        @endif
    </div>
</div>
@endsection

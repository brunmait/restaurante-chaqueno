@extends('layouts.cajero')

@section('title', 'Consultar Stock')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-search mr-3 text-teal-600"></i>
            Consultar Stock
        </h1>
        <p class="text-gray-600 mt-1">Estado actual del inventario y disponibilidad</p>
    </div>
    <a href="{{ route('cashier.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i>
        <span>Volver</span>
    </a>
</div>

@php
    $stockController = new \App\Http\Controllers\StockController();
    $chanchoInfo = $stockController->getChanchoStockInfo();
    $polloInfo = $stockController->getPolloStockInfo();
    $pesoCostillaPromKg = (float) (\App\Models\Setting::get('peso_costilla_prom_kg', 1.2));
    $gramosCostilla = (int) (\App\Models\Setting::get('gramos_costilla', 375));
    $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 2));
    $consumoKgPorPlatoBase = max(0.000001, $pesoCostillaPromKg / 8);
    $platosChancho = (int) floor(($chanchoInfo['stock_actual'] / $consumoKgPorPlatoBase));
    $platosPollo = (int) floor($polloInfo['stock_actual'] * max(1,$porcionesPorPollo));
@endphp

<!-- Stock Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Chancho Stock -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
            <div class="flex items-center">
                <span class="text-white text-2xl mr-3">🥩</span>
                <h3 class="text-white text-lg font-semibold">Stock de Chancho</h3>
            </div>
        </div>
        <div class="p-6 text-center">
            <div class="text-3xl font-bold text-orange-600 mb-2">{{ number_format($chanchoInfo['stock_actual'],2) }} kg</div>
            <div class="space-y-2 text-sm text-gray-600">
                <p>Platos disponibles: <span class="font-semibold text-gray-900">{{ $platosChancho }}</span></p>
                <p>Peso promedio costilla: <span class="font-semibold">{{ number_format($pesoCostillaPromKg,2) }} kg</span></p>
            </div>
            @if($chanchoInfo['stock_actual'] < \App\Models\Setting::get('stock_min_kilos_chancho', 5))
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center justify-center text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="text-sm font-medium">Stock bajo - Mínimo: {{ \App\Models\Setting::get('stock_min_kilos_chancho', 5) }} kg</span>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Pollo Stock -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <div class="flex items-center">
                <span class="text-white text-2xl mr-3">🐔</span>
                <h3 class="text-white text-lg font-semibold">Stock de Pollo</h3>
            </div>
        </div>
        <div class="p-6 text-center">
            <div class="text-3xl font-bold text-yellow-600 mb-2">{{ number_format($polloInfo['stock_actual'],2) }}</div>
            <div class="text-sm text-gray-500 mb-3">pollos enteros</div>
            <div class="space-y-2 text-sm text-gray-600">
                <p>Porciones disponibles: <span class="font-semibold text-gray-900">{{ $platosPollo }}</span></p>
                <p>Configuración: <span class="font-semibold">{{ $porcionesPorPollo }} porciones/pollo</span></p>
            </div>
            @if($polloInfo['stock_actual'] < \App\Models\Setting::get('stock_min_pollos', 3))
                <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                    <div class="flex items-center justify-center text-yellow-800">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span class="text-sm font-medium">Stock bajo - Mínimo: {{ \App\Models\Setting::get('stock_min_pollos', 3) }} pollos</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Detalle de Productos -->
<div class="card border-danger bg-accent-soft shadow-sm">
    <div class="card-header bg-white border-accent">
        <h5 class="mb-0 theme-accent">
            <i class="fas fa-boxes me-2"></i>Detalle de Productos
        </h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock Actual</th>
                        <th>Stock Mínimo</th>
                        <th>Estado</th>
                        <th>Platos Disponibles</th>
                        <th>Última Actualización</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $productos = \DB::table('productos')->select('id','nombre','categoria','stock')->get();
                        $chanchoId = (new \App\Http\Controllers\StockController())->getChanchoProductId();
                        $polloId = (new \App\Http\Controllers\StockController())->getPolloProductId();
                    @endphp
                    @forelse($productos as $producto)
                        @php
                            $stockActual = 0; $stockMinimo = 0; $platosDisponibles = 0; $ultimaActualizacion = '-';
                            if($producto->id == $chanchoId) {
                                $stockActual = $chanchoInfo['stock_actual'];
                                $stockMinimo = \App\Models\Setting::get('stock_min_kilos_chancho', 5);
                                $platosDisponibles = $platosChancho;
                            } elseif($producto->id == $polloId) {
                                $stockActual = $polloInfo['stock_actual'];
                                $stockMinimo = \App\Models\Setting::get('stock_min_pollos', 3);
                                $platosDisponibles = $platosPollo;
                            } else {
                                $stockActual = (float)($producto->stock ?? 0);
                                $stockMinimo = 0; $platosDisponibles = 0;
                            }
                            $ultimoMovimiento = \DB::table('stock')->where('producto_id', $producto->id)->orderBy('fecha','desc')->first();
                            if($ultimoMovimiento) { $ultimaActualizacion = \Carbon\Carbon::parse($ultimoMovimiento->fecha)->format('d/m/Y H:i'); }
                        @endphp
                        <tr>
                            <td>
                                <i class="fas {{ $producto->id == $chanchoId ? 'fa-drumstick-bite' : ($producto->id == $polloId ? 'fa-chicken' : 'fa-box') }} me-2"></i>
                                {{ $producto->nombre }}
                            </td>
                            <td>
                                @if($producto->id == $chanchoId)
                                    <strong>{{ number_format($stockActual,2) }} kg</strong>
                                @elseif($producto->id == $polloId)
                                    <strong>{{ number_format($stockActual,2) }} pollos</strong>
                                @else
                                    <strong>{{ number_format($stockActual,2) }}</strong>
                                @endif
                            </td>
                            <td>
                                @if($producto->id == $chanchoId)
                                    {{ $stockMinimo }} kg
                                @elseif($producto->id == $polloId)
                                    {{ $stockMinimo }} pollos
                                @else
                                    {{ $stockMinimo }}
                                @endif
                            </td>
                            <td>
                                @if($stockActual >= $stockMinimo)
                                    <span class="badge bg-success">Disponible</span>
                                @else
                                    <span class="badge bg-danger">Stock Bajo</span>
                                @endif
                            </td>
                            <td><strong>{{ $platosDisponibles }}</strong></td>
                            <td>{{ $ultimaActualizacion }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay productos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Información de Precios -->
<div class="card border-danger bg-accent-soft shadow-sm mt-4">
    <div class="card-header bg-white border-accent">
        <h5 class="mb-0 theme-accent">
            <i class="fas fa-tags me-2"></i>Precios de Venta
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6 class="theme-accent">Chancho</h6>
                <ul class="list-unstyled">
                    <li>• 1 costilla ({{ $gramosCostilla * 1 }}g): <strong>Bs. 50</strong></li>
                    <li>• 2 costillas ({{ $gramosCostilla * 2 }}g): <strong>Bs. 60</strong></li>
                    <li>• 2.5 costillas ({{ $gramosCostilla * 2.5 }}g): <strong>Bs. 70</strong></li>
                    <li>• 3 costillas ({{ $gramosCostilla * 3 }}g): <strong>Bs. 80</strong></li>
                    <li>• 3.5 costillas ({{ $gramosCostilla * 3.5 }}g): <strong>Bs. 90</strong></li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6 class="theme-accent">Pollo</h6>
                <ul class="list-unstyled">
                    <li>• 1 unidad (1/{{ max(1,$porcionesPorPollo) }} pollo): <strong>Bs. 60</strong></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@endsection

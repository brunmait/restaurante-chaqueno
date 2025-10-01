@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Vista General del Inventario</h1>
        <p class="text-slate-600 mt-2">Resumen completo del stock disponible</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.stock') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Gestionar Stock</span>
        </a>
    </div>
</div>
@php
    // Separar productos por categoría
    $insumos = $productos->where('categoria', 'insumo');
    $platosChancho = $productos->where('categoria', 'chancho');
    $platosPollo = $productos->where('categoria', 'pollo');
    $refrescos = $productos->where('categoria', 'refresco');
    
    // Calcular stock de carne disponible
    $carneDisponible = 0;
    $polloDisponible = 0;
    $gramosCostilla = (int) \App\Models\Setting::get('gramos_costilla', 375); // g por costilla
    $porcionesPorPollo = (int) \App\Models\Setting::get('porciones_por_pollo', 2);
    
    foreach($insumos as $insumo) {
        if(str_contains(strtolower($insumo->nombre), 'chancho')) { $carneDisponible = $insumo->stock ?? 0; }
        if(str_contains(strtolower($insumo->nombre), 'pollo')) { $polloDisponible = $insumo->stock ?? 0; }
    }
@endphp

<!-- Resumen de Stock Principal -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-red-100 p-3 rounded-lg">
                <i class="fas fa-drumstick-bite text-red-600 text-xl"></i>
            </div>
            @php $kgPorPlato3 = (3 * $gramosCostilla) / 1000; @endphp
            <span class="text-2xl font-bold text-slate-900">{{ $kgPorPlato3 > 0 ? floor($carneDisponible / $kgPorPlato3) : 0 }}</span>
        </div>
        <h3 class="font-semibold text-slate-900 mb-1">Platos de Chancho</h3>
        <p class="text-slate-600 text-sm">{{ number_format($carneDisponible, 1) }} kg disponibles</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i class="fas fa-egg text-yellow-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-slate-900">{{ floor($polloDisponible * max(1,$porcionesPorPollo)) }}</span>
        </div>
        <h3 class="font-semibold text-slate-900 mb-1">Porciones de Pollo</h3>
        <p class="text-slate-600 text-sm">{{ number_format($polloDisponible, 2) }} pollos disponibles</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i class="fas fa-wine-bottle text-blue-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-slate-900">{{ $refrescos->sum('stock') }}</span>
        </div>
        <h3 class="font-semibold text-slate-900 mb-1">Refrescos</h3>
        <p class="text-slate-600 text-sm">{{ $refrescos->count() }} tipos disponibles</p>
    </div>
    
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i class="fas fa-boxes text-green-600 text-xl"></i>
            </div>
            <span class="text-2xl font-bold text-slate-900">{{ $productos->count() }}</span>
        </div>
        <h3 class="font-semibold text-slate-900 mb-1">Total Productos</h3>
        <p class="text-slate-600 text-sm">En inventario</p>
    </div>
</div>

<!-- Refrescos -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-blue-100 p-2 rounded-lg">
                <i class="fas fa-wine-bottle text-blue-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Refrescos</h2>
        </div>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Producto</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Precio</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Stock</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($refrescos as $refresco)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $refresco->nombre }}</td>
                            <td class="py-3 px-4 text-slate-600">Bs. {{ number_format($refresco->precio ?? 0, 0) }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ number_format($refresco->stock ?? 0, 0) }} unidades</td>
                            <td class="py-3 px-4">
                                @if(($refresco->stock ?? 0) > 0)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Disponible</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Agotado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-500">Sin refrescos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chancho a la Cruz -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-red-100 p-2 rounded-lg">
                <i class="fas fa-drumstick-bite text-red-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Chancho a la Cruz</h2>
        </div>
    </div>
    <div class="p-6">
        <div class="bg-slate-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-slate-900 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
                Resumen de Disponibilidad
            </h3>
            <p class="text-slate-600 mb-3">Con {{ number_format($carneDisponible, 1) }} kg de carne puedes hacer:</p>
            @php
                $kg2 = (2 * $gramosCostilla) / 1000;
                $kg25 = (2.5 * $gramosCostilla) / 1000;
                $kg3 = (3 * $gramosCostilla) / 1000;
                $kg35 = (3.5 * $gramosCostilla) / 1000;
            @endphp
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $kg2 > 0 ? floor($carneDisponible / $kg2) : 0 }}</div>
                    <div class="text-sm text-slate-600">2 costillas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $kg25 > 0 ? floor($carneDisponible / $kg25) : 0 }}</div>
                    <div class="text-sm text-slate-600">2.5 costillas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $kg3 > 0 ? floor($carneDisponible / $kg3) : 0 }}</div>
                    <div class="text-sm text-slate-600">3 costillas</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $kg35 > 0 ? floor($carneDisponible / $kg35) : 0 }}</div>
                    <div class="text-sm text-slate-600">3.5 costillas</div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Plato</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Precio</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Disponibles</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($platosChancho as $plato)
                        @php
                            $kilosPorPlato = null;
                            if (stripos($plato->nombre, '2.5') !== false) {
                                $kilosPorPlato = (2.5 * $gramosCostilla) / 1000;
                            } elseif (stripos($plato->nombre, '3.5') !== false) {
                                $kilosPorPlato = (3.5 * $gramosCostilla) / 1000;
                            } elseif (stripos($plato->nombre, '3 ') !== false || stripos($plato->nombre, ' 3 C') !== false) {
                                $kilosPorPlato = (3 * $gramosCostilla) / 1000;
                            } elseif (stripos($plato->nombre, '2 ') !== false || stripos($plato->nombre, ' 2 C') !== false) {
                                $kilosPorPlato = (2 * $gramosCostilla) / 1000;
                            }
                            $disponibles = ($carneDisponible > 0 && $kilosPorPlato && $kilosPorPlato > 0)
                                ? floor($carneDisponible / $kilosPorPlato)
                                : 0;
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $plato->nombre }}</td>
                            <td class="py-3 px-4 text-slate-600">Bs. {{ number_format($plato->precio ?? 0, 0) }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $disponibles }} platos</td>
                            <td class="py-3 px-4">
                                @if($disponibles > 0)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Disponible</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Sin carne</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-500">Sin platos de chancho registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pollo a la Leña -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 mb-8">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-yellow-100 p-2 rounded-lg">
                <i class="fas fa-egg text-yellow-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Pollo a la Leña</h2>
        </div>
    </div>
    <div class="p-6">
        <div class="bg-slate-50 rounded-lg p-4 mb-6">
            <h3 class="font-semibold text-slate-900 mb-3 flex items-center">
                <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
                Resumen de Disponibilidad
            </h3>
            <p class="text-slate-600 mb-3">Con {{ number_format($polloDisponible, 2) }} pollos disponibles:</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ floor($polloDisponible * max(1,$porcionesPorPollo)) }}</div>
                    <div class="text-sm text-slate-600">Porciones totales</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-emerald-600">{{ $porcionesPorPollo }}</div>
                    <div class="text-sm text-slate-600">Porciones por pollo</div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Plato</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Precio</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Disponibles</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($platosPollo as $plato)
                        @php
                            $porcionesPorPlato = 1;
                            if (stripos($plato->nombre, 'media') !== false) { $porcionesPorPlato = 0.5; }
                            $disponibles = ($polloDisponible > 0 && $porcionesPorPollo > 0) ? floor($polloDisponible * ($porcionesPorPollo / $porcionesPorPlato)) : 0;
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $plato->nombre }}</td>
                            <td class="py-3 px-4 text-slate-600">Bs. {{ number_format($plato->precio ?? 0, 0) }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ $disponibles }} platos</td>
                            <td class="py-3 px-4">
                                @if($disponibles > 0)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Disponible</span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Sin pollo</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-500">Sin platos de pollo registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Insumos (Materia Prima) -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-purple-100 p-2 rounded-lg">
                <i class="fas fa-boxes text-purple-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Insumos (Materia Prima)</h2>
        </div>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Producto</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Precio</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Stock</th>
                        <th class="text-left py-3 px-4 font-semibold text-slate-700">Estado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($insumos as $insumo)
                        <tr class="border-b border-slate-100 hover:bg-slate-50">
                            <td class="py-3 px-4 font-medium text-slate-900">{{ $insumo->nombre }}</td>
                            <td class="py-3 px-4 text-slate-600">Bs. {{ number_format($insumo->precio ?? 0, 0) }}/{{ str_contains(strtolower($insumo->nombre), 'chancho') ? 'kg' : 'unidad' }}</td>
                            <td class="py-3 px-4 text-slate-600">{{ number_format($insumo->stock ?? 0, 1) }} {{ str_contains(strtolower($insumo->nombre), 'chancho') ? 'kg' : 'unidades' }}</td>
                            <td class="py-3 px-4">
                                @if(($insumo->stock ?? 0) > 0)
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">Disponible</span>
                                @else
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Agotado</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center py-8 text-slate-500">Sin insumos registrados</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Reportes de Inventario</h1>
        <p class="text-slate-600 mt-2">Análisis detallado de movimientos por período</p>
    </div>
    <div class="flex space-x-3">
        <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-print"></i>
            <span>Imprimir</span>
        </button>
        <a href="{{ route('admin.inventory.movements') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-exchange-alt"></i>
            <span>Ver Movimientos</span>
        </a>
    </div>
</div>

<!-- Filtros de fecha -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
    <form method="get" action="{{ route('admin.inventory.report') }}" class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                Fecha de Inicio
            </label>
            <input type="date" name="desde" value="{{ $desde }}" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        
        <div class="flex-1 min-w-48">
            <label class="block text-sm font-medium text-slate-700 mb-2">
                <i class="fas fa-calendar-alt mr-1"></i>
                Fecha de Fin
            </label>
            <input type="date" name="hasta" value="{{ $hasta }}" 
                   class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        </div>
        
        <div class="flex space-x-2">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center space-x-2">
                <i class="fas fa-search"></i>
                <span>Filtrar</span>
            </button>
            <a href="{{ route('admin.inventory.report') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-2 rounded-lg transition-colors flex items-center">
                <i class="fas fa-times"></i>
            </a>
        </div>
    </form>
</div>

@if($desde || $hasta)
    <!-- Resumen del período -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
        <h3 class="text-lg font-semibold text-slate-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar text-emerald-600 mr-2"></i>
            Resumen del Período
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $entradas = $movs->where('tipo', 'entrada');
                $salidas = $movs->where('tipo', 'salida');
            @endphp
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $entradas->count() }}</div>
                <div class="text-slate-600">Entradas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ $salidas->count() }}</div>
                <div class="text-slate-600">Salidas</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-slate-900">{{ $movs->count() }}</div>
                <div class="text-slate-600">Total Movimientos</div>
            </div>
        </div>
    </div>
@endif

<!-- Tabla de reportes -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-slate-900">Detalle de Movimientos</h2>
            </div>
            @if($desde || $hasta)
                <div class="text-sm text-slate-600">
                    @if($desde && $hasta)
                        {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
                    @elseif($desde)
                        Desde {{ \Carbon\Carbon::parse($desde)->format('d/m/Y') }}
                    @elseif($hasta)
                        Hasta {{ \Carbon\Carbon::parse($hasta)->format('d/m/Y') }}
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-slate-200 bg-slate-50">
                    <th class="text-left py-4 px-6 font-semibold text-slate-700">Fecha y Hora</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700">Producto</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700">Tipo</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700">Cantidad</th>
                    <th class="text-left py-4 px-6 font-semibold text-slate-700">Unidad</th>
                </tr>
            </thead>
            <tbody>
                @forelse($movs as $m)
                    @php $prod = \DB::table('productos')->where('id',$m->producto_id)->first(); @endphp
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($m->fecha)->format('d/m/Y') }}</div>
                            <div class="text-slate-500 text-sm">{{ \Carbon\Carbon::parse($m->fecha)->format('H:i') }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="font-medium text-slate-900">{{ $prod->nombre ?? 'Producto #'.$m->producto_id }}</div>
                            @if(isset($prod->categoria))
                                <div class="text-slate-500 text-sm capitalize">{{ $prod->categoria }}</div>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            @if($m->tipo === 'entrada')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium flex items-center w-fit">
                                    <i class="fas fa-arrow-up mr-1"></i>
                                    Entrada
                                </span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium flex items-center w-fit">
                                    <i class="fas fa-arrow-down mr-1"></i>
                                    Salida
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-slate-900 font-medium">{{ number_format($m->cantidad, $m->cantidad == floor($m->cantidad) ? 0 : 2) }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <span class="text-slate-600">{{ $m->unidad ?? ( (isset($prod) && ($prod->categoria==='insumo')) ? 'kg' : 'unidades' ) }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-12">
                            <div class="text-slate-500">
                                <i class="fas fa-chart-line text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No hay datos para mostrar</p>
                                <p class="text-sm">Selecciona un rango de fechas para generar el reporte</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

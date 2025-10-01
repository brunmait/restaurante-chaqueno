@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Movimientos de Inventario</h1>
        <p class="text-slate-600 mt-2">Historial de entradas y salidas de stock</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.inventory.report') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-chart-line"></i>
            <span>Ver Reportes</span>
        </a>
        <a href="{{ route('admin.stock') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nuevo Movimiento</span>
        </a>
    </div>
</div>

<!-- Filtros rápidos -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 mb-8">
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center space-x-2">
            <i class="fas fa-filter text-slate-500"></i>
            <span class="text-slate-700 font-medium">Filtros:</span>
        </div>
        <button class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium hover:bg-emerald-200 transition-colors">
            Todos
        </button>
        <button class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm hover:bg-slate-200 transition-colors">
            Entradas
        </button>
        <button class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm hover:bg-slate-200 transition-colors">
            Salidas
        </button>
        <button class="bg-slate-100 text-slate-700 px-3 py-1 rounded-full text-sm hover:bg-slate-200 transition-colors">
            Hoy
        </button>
    </div>
</div>

<!-- Tabla de movimientos -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-purple-100 p-2 rounded-lg">
                <i class="fas fa-exchange-alt text-purple-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Historial de Movimientos</h2>
            <span class="bg-slate-100 text-slate-700 px-2 py-1 rounded-full text-sm">Últimos 200 registros</span>
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
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p class="text-lg font-medium">No hay movimientos registrados</p>
                                <p class="text-sm">Los movimientos de inventario aparecerán aquí</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('layouts.cajero')

@section('title', 'Pedidos en Línea')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Pedidos en Línea</h1>
        <p class="text-gray-600 mt-1">Gestiona los pedidos realizados por los clientes</p>
    </div>
    <a href="{{ route('cashier.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
        <i class="fas fa-arrow-left"></i>
        <span>Volver</span>
    </a>
</div>

<!-- Debug Info (temporal) -->
@if(isset($debugInfo))
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <h4 class="font-semibold text-blue-800 mb-2">Debug Info:</h4>
    <div class="text-sm text-blue-700">
        <p>Pedidos Online: {{ $debugInfo['pedidos_online'] }}</p>
        <p>Ventas Directas: {{ $debugInfo['ventas_directas'] }}</p>
        <p>Total Pedidos: {{ $debugInfo['total_pedidos'] }}</p>
        <p>Session Receipts: {{ $debugInfo['session_receipts'] }}</p>
    </div>
</div>
@endif

<!-- Orders List -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
            <i class="fas fa-receipt mr-3 text-chaqueno-600"></i>
            Lista de Pedidos
        </h3>
    </div>
    
    @if($pedidos->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Hora</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Detalle</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Pago</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @foreach($pedidos as $p)
                        @php $items = $p->items_json ? json_decode($p->items_json, true) : []; @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($p->creado_en)->format('d/m H:i') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($p->creado_en)->diffForHumans() }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($p->tipo === 'directa')
                                        <div class="w-10 h-10 bg-gradient-to-r from-chaqueno-500 to-chaqueno-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                            <i class="fas fa-cash-register text-xs"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-medium text-sm">
                                            {{ strtoupper(substr($p->cliente_nombre, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $p->cliente_nombre }}</div>
                                        <div class="text-sm text-gray-500">
                                            {{ $p->cliente_telefono }}
                                            @if($p->tipo === 'directa')
                                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-chaqueno-100 text-chaqueno-800">
                                                    Venta Directa
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($items)
                                    <div class="space-y-1">
                                        @foreach($items as $it)
                                            <div class="flex items-center justify-between bg-gray-50 rounded px-2 py-1">
                                                <span class="text-sm text-gray-700">{{ ucfirst($it['tipo'] ?? 'item') }} Bs. {{ $it['precio'] ?? 0 }}</span>
                                                <span class="text-sm font-medium text-gray-900">x{{ $it['cantidad'] ?? 1 }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Sin detalles</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-lg font-bold text-green-600">Bs. {{ number_format($p->monto, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($p->estado_pago === 'pagado')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Pagado
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>
                                        Pendiente
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @if($p->tipo === 'directa')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-chaqueno-100 text-chaqueno-800">
                                        <i class="fas fa-check mr-1"></i>Completada
                                    </span>
                                @else
                                    <div class="flex justify-end space-x-2">
                                        <form method="post" action="{{ route('cashier.orders.accept', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" title="Atender">
                                                <i class="fas fa-play mr-1"></i>Atender
                                            </button>
                                        </form>
                                        
                                        <form method="post" action="{{ route('cashier.orders.paid', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-chaqueno-600 hover:bg-chaqueno-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" title="Marcar como pagado">
                                                <i class="fas fa-dollar-sign mr-1"></i>Pagado
                                            </button>
                                        </form>
                                        
                                        <form method="post" action="{{ route('cashier.orders.cancel', $p->id) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" title="Cancelar pedido" onclick="return confirm('¿Estás seguro de cancelar este pedido?')">
                                                <i class="fas fa-times mr-1"></i>Cancelar
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="px-6 py-12 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-receipt text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No hay pedidos</h3>
            <p class="text-gray-500">Los pedidos realizados por los clientes aparecerán aquí</p>
        </div>
    @endif
</div>
@endsection



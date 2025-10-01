@extends('layouts.cajero')

@section('content')
<div class="bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-700 rounded-2xl overflow-hidden mb-8 shadow-2xl">
    <div class="bg-gradient-to-r from-slate-900/70 to-slate-800/50 p-8">
        <div class="text-white">
            <h3 class="text-3xl font-bold mb-3 flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                Comprobantes de Venta
            </h3>
            <div class="text-purple-100 text-lg font-medium">Historial de todas las ventas</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Total Ventas</p>
                <p class="text-2xl font-bold text-purple-600">{{ $comprobantes->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-receipt text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Ventas Directas</p>
                <p class="text-2xl font-bold text-green-600">{{ $comprobantes->where('cliente_nombre', 'Venta Directa - Cajero')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-cash-register text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Pedidos Online</p>
                <p class="text-2xl font-bold text-blue-600">{{ $comprobantes->where('cliente_nombre', '!=', 'Venta Directa - Cajero')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-globe text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Total Ingresos</p>
                <p class="text-2xl font-bold text-emerald-600">{{ number_format($comprobantes->sum('total'), 0) }} Bs</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white"></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-lg border border-slate-200">
    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 rounded-t-2xl">
        <h3 class="text-lg font-semibold flex items-center text-slate-800">
            <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-list text-white text-sm"></i>
            </div>
            Historial de Comprobantes
        </h3>
    </div>
    
    <div class="p-6">
        @if($comprobantes->count() > 0)
            <div class="space-y-4">
                @foreach($comprobantes as $comprobante)
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ $comprobante->numero_ticket }}
                            </div>
                            <div>
                                <div class="font-bold text-lg text-slate-800">
                                    @if($comprobante->cliente_nombre === 'Venta Directa - Cajero')
                                        <span class="text-green-600">Venta Directa</span>
                                    @else
                                        {{ $comprobante->cliente_nombre }}
                                    @endif
                                </div>
                                <div class="text-sm text-slate-600">
                                    @if($comprobante->cliente_telefono !== 'N/A')
                                        📞 {{ $comprobante->cliente_telefono }}
                                    @else
                                        💰 Venta en caja
                                    @endif
                                </div>
                                <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($comprobante->fecha_pedido)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-emerald-600">{{ number_format($comprobante->total, 0) }} Bs</div>
                                @php
                                    $items = json_decode($comprobante->items, true);
                                @endphp
                                <div class="text-sm text-slate-600">{{ count($items) }} productos</div>
                            </div>
                            
                            <div class="flex flex-col space-y-2">
                                <a href="{{ route('cashier.comprobantes.show', $comprobante->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-eye mr-1"></i>Ver
                                </a>
                                <a href="{{ route('cashier.comprobantes.qr', $comprobante->id) }}" target="_blank"
                                   class="bg-purple-600 hover:bg-purple-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                                    <i class="fas fa-qrcode mr-1"></i>QR
                                </a>
                            </div>
                        </div>
                    </div>
                    
                    @if($items && count($items) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="text-sm font-medium text-slate-700 mb-2">Productos vendidos:</div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                            @foreach($items as $item)
                            <div class="bg-white rounded-lg p-2 border border-slate-200">
                                <div class="text-sm">
                                    <span class="font-medium">{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</span>
                                    @if(isset($item['acompanamiento']))
                                        <span class="text-slate-600"> con {{ $item['acompanamiento'] }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-emerald-600 font-bold">{{ number_format($item['precio'], 0) }} Bs</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-receipt text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-600 mb-2">No hay comprobantes</h3>
                <p class="text-slate-500">Los comprobantes de venta aparecerán aquí</p>
            </div>
        @endif
    </div>
</div>
@endsection
@extends('layouts.cajero')

@section('content')
<div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-2xl overflow-hidden mb-8 shadow-2xl">
    <div class="bg-gradient-to-r from-slate-900/70 to-slate-800/50 p-8">
        <div class="text-white">
            <h3 class="text-3xl font-bold mb-3 flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-shopping-cart text-2xl"></i>
                </div>
                Pedidos en Línea
            </h3>
            <div class="text-blue-100 text-lg font-medium">Gestión de pedidos online</div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Pendientes</p>
                <p class="text-2xl font-bold text-orange-600">{{ $pedidosOnline->where('estado', 'pendiente')->count() + $ventasDirectas->where('estado', 'pendiente')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-clock text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Preparando</p>
                <p class="text-2xl font-bold text-blue-600">{{ $pedidosOnline->where('estado', 'preparando')->count() + $ventasDirectas->where('estado', 'preparando')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-fire text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Listos</p>
                <p class="text-2xl font-bold text-green-600">{{ $pedidosOnline->where('estado', 'listo')->count() + $ventasDirectas->where('estado', 'listo')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-check text-white"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-600">Entregados</p>
                <p class="text-2xl font-bold text-slate-600">{{ $pedidosOnline->where('estado', 'entregado')->count() + $ventasDirectas->where('estado', 'entregado')->count() }}</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-r from-slate-500 to-slate-600 rounded-xl flex items-center justify-center">
                <i class="fas fa-check-double text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Pedidos Online -->
@if($pedidosOnline->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-8">
    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-100 rounded-t-2xl">
        <h3 class="text-lg font-semibold flex items-center text-slate-800">
            <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-globe text-white text-sm"></i>
            </div>
            Pedidos Online
        </h3>
    </div>
    
    <div class="p-6">
        <div class="space-y-4">
            @foreach($pedidosOnline as $pedido)
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ $pedido->numero_ticket }}
                            </div>
                            <div>
                                <div class="font-bold text-lg text-slate-800">{{ $pedido->cliente_nombre }}</div>
                                <div class="text-sm text-slate-600">📞 {{ $pedido->cliente_telefono }}</div>
                                <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-emerald-600">{{ number_format($pedido->total, 0) }} Bs</div>
                                @php
                                    $items = json_decode($pedido->items, true);
                                @endphp
                                <div class="text-sm text-slate-600">{{ count($items) }} productos</div>
                            </div>
                            
                            <div class="flex flex-col space-y-2">
                                @php
                                    $estadoColors = [
                                        'pendiente' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'preparando' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'listo' => 'bg-green-100 text-green-800 border-green-200',
                                        'entregado' => 'bg-slate-100 text-slate-800 border-slate-200',
                                        'cancelado' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $estadoColors[$pedido->estado] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                                
                                @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                                <select onchange="cambiarEstado({{ $pedido->id }}, this.value)" class="text-xs border border-slate-300 rounded-lg px-2 py-1">
                                    <option value="">Cambiar estado</option>
                                    @if($pedido->estado === 'pendiente')
                                        <option value="preparando">Preparando</option>
                                        <option value="cancelado">Cancelar</option>
                                    @elseif($pedido->estado === 'preparando')
                                        <option value="listo">Listo</option>
                                        <option value="cancelado">Cancelar</option>
                                    @elseif($pedido->estado === 'listo')
                                        <option value="entregado">Entregado</option>
                                    @endif
                                </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($items && count($items) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="text-sm font-medium text-slate-700 mb-2">Detalles del pedido:</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($items as $item)
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</span>
                                        @if(isset($item['acompanamiento']))
                                            <span class="text-slate-600"> con {{ $item['acompanamiento'] }}</span>
                                        @endif
                                    </div>
                                    <span class="font-bold text-emerald-600">{{ number_format($item['precio'], 0) }} Bs</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($pedido->notas)
                    <div class="mt-3 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <div class="text-sm font-medium text-yellow-800">Notas:</div>
                        <div class="text-sm text-yellow-700">{{ $pedido->notas }}</div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Ventas Directas -->
@if($ventasDirectas->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-slate-200">
    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-green-50 to-emerald-100 rounded-t-2xl">
        <h3 class="text-lg font-semibold flex items-center text-slate-800">
            <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mr-3">
                <i class="fas fa-cash-register text-white text-sm"></i>
            </div>
            Ventas Directas
        </h3>
    </div>
    
    <div class="p-6">
        <div class="space-y-4">
            @foreach($ventasDirectas as $pedido)
                <div class="bg-gradient-to-r from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg shadow-lg">
                                {{ $pedido->numero_ticket }}
                            </div>
                            <div>
                                <div class="font-bold text-lg text-slate-800">Venta Directa</div>
                                <div class="text-sm text-slate-500">{{ \Carbon\Carbon::parse($pedido->fecha_pedido)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-2xl font-bold text-emerald-600">{{ number_format($pedido->total, 0) }} Bs</div>
                                @php
                                    $items = json_decode($pedido->items, true);
                                @endphp
                                <div class="text-sm text-slate-600">{{ count($items) }} productos</div>
                            </div>
                            
                            <div class="flex flex-col space-y-2">
                                @php
                                    $estadoColors = [
                                        'pendiente' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'preparando' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'listo' => 'bg-green-100 text-green-800 border-green-200',
                                        'entregado' => 'bg-slate-100 text-slate-800 border-slate-200',
                                        'cancelado' => 'bg-red-100 text-red-800 border-red-200'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $estadoColors[$pedido->estado] ?? 'bg-slate-100 text-slate-800 border-slate-200' }}">
                                    {{ ucfirst($pedido->estado) }}
                                </span>
                                
                                @if($pedido->estado !== 'entregado' && $pedido->estado !== 'cancelado')
                                <select onchange="cambiarEstado({{ $pedido->id }}, this.value)" class="text-xs border border-slate-300 rounded-lg px-2 py-1">
                                    <option value="">Cambiar estado</option>
                                    @if($pedido->estado === 'pendiente')
                                        <option value="preparando">Preparando</option>
                                        <option value="cancelado">Cancelar</option>
                                    @elseif($pedido->estado === 'preparando')
                                        <option value="listo">Listo</option>
                                        <option value="cancelado">Cancelar</option>
                                    @elseif($pedido->estado === 'listo')
                                        <option value="entregado">Entregado</option>
                                    @endif
                                </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($items && count($items) > 0)
                    <div class="mt-4 pt-4 border-t border-slate-200">
                        <div class="text-sm font-medium text-slate-700 mb-2">Detalles del pedido:</div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach($items as $item)
                            <div class="bg-white rounded-lg p-3 border border-slate-200">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <span class="font-medium">{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</span>
                                        @if(isset($item['acompanamiento']))
                                            <span class="text-slate-600"> con {{ $item['acompanamiento'] }}</span>
                                        @endif
                                    </div>
                                    <span class="font-bold text-emerald-600">{{ number_format($item['precio'], 0) }} Bs</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif

@if($pedidosOnline->count() == 0 && $ventasDirectas->count() == 0)
<div class="bg-white rounded-2xl shadow-lg border border-slate-200">
    <div class="p-12 text-center">
        <div class="w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-shopping-cart text-3xl text-slate-400"></i>
        </div>
        <h3 class="text-lg font-medium text-slate-600 mb-2">No hay pedidos</h3>
        <p class="text-slate-500">Los pedidos aparecerán aquí cuando se realicen</p>
    </div>
</div>
@endif

<script>
function cambiarEstado(pedidoId, nuevoEstado) {
    if (!nuevoEstado) return;
    
    fetch(`/cashier/pedidos/${pedidoId}/estado`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al cambiar el estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cambiar el estado');
    });
}
</script>
@endsection
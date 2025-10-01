<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla de Pedidos - Restaurante El Chaqueño</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <meta http-equiv="refresh" content="30">
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 min-h-screen">
    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 shadow-2xl">
        <div class="container mx-auto px-6 py-8">
            <div class="text-center text-white">
                <h1 class="text-5xl font-bold mb-4 flex items-center justify-center">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mr-6">
                        <i class="fas fa-utensils text-3xl"></i>
                    </div>
                    Restaurante El Chaqueño
                </h1>
                <div class="text-2xl font-medium text-emerald-100">Estado de Pedidos en Tiempo Real</div>
                <div class="mt-4 flex items-center justify-center space-x-3">
                    <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse"></div>
                    <span class="text-green-200">Sistema en línea</span>
                    <span class="text-emerald-200 mx-4">•</span>
                    <span class="text-emerald-200">Actualización automática cada 30 segundos</span>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-6 py-8">
        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl p-6 text-white shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">{{ $pedidos->where('estado', 'pendiente')->count() }}</div>
                        <div class="text-orange-100 font-medium">Pendientes</div>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-clock text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl p-6 text-white shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">{{ $pedidos->where('estado', 'preparando')->count() }}</div>
                        <div class="text-blue-100 font-medium">Preparando</div>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-fire text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl p-6 text-white shadow-2xl">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-3xl font-bold">{{ $pedidos->where('estado', 'listo')->count() }}</div>
                        <div class="text-green-100 font-medium">Listos para Recoger</div>
                    </div>
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($pedidos->count() > 0)
            <!-- Pedidos Listos -->
            @php $pedidosListos = $pedidos->where('estado', 'listo') @endphp
            @if($pedidosListos->count() > 0)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-green-600 to-emerald-700 rounded-t-2xl p-6 text-white">
                    <h2 class="text-3xl font-bold flex items-center">
                        <i class="fas fa-check-circle mr-4"></i>
                        Pedidos Listos para Recoger
                    </h2>
                </div>
                <div class="bg-white rounded-b-2xl shadow-2xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pedidosListos as $pedido)
                        <div class="bg-gradient-to-br from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-6 shadow-lg animate-pulse">
                            <div class="text-center">
                                <div class="w-20 h-20 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center text-white font-bold text-2xl mx-auto mb-4 shadow-lg">
                                    {{ $pedido->numero_ticket }}
                                </div>
                                <div class="text-2xl font-bold text-slate-800 mb-2">{{ $pedido->cliente_nombre }}</div>
                                @if($pedido->cliente_telefono !== 'N/A')
                                    <div class="text-lg text-slate-600 mb-2">📞 {{ $pedido->cliente_telefono }}</div>
                                @endif
                                
                                @php $items = json_decode($pedido->items, true); @endphp
                                @if($items && count($items) > 0)
                                    <div class="bg-white rounded-lg p-3 mb-3 border border-green-200">
                                        <div class="text-sm font-medium text-slate-700 mb-2">Pedido:</div>
                                        @foreach($items as $item)
                                            <div class="text-sm text-slate-600 mb-2 p-2 bg-green-50 rounded border">
                                                <div class="flex justify-between items-start">
                                                    <div class="font-medium">{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</div>
                                                    <div class="text-green-700 font-bold">Bs.{{ $item['precio'] ?? 0 }}</div>
                                                </div>
                                                @if(isset($item['acompanamiento']))
                                                    <div class="text-green-600 font-medium text-xs mt-1">
                                                        🍽️ {{ ucfirst($item['acompanamiento']) }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="bg-green-500 text-white px-4 py-2 rounded-full font-bold text-lg">
                                    ¡LISTO!
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Pedidos en Preparación -->
            @php $pedidosPreparando = $pedidos->where('estado', 'preparando') @endphp
            @if($pedidosPreparando->count() > 0)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-t-2xl p-6 text-white">
                    <h2 class="text-3xl font-bold flex items-center">
                        <i class="fas fa-fire mr-4"></i>
                        Pedidos en Preparación
                    </h2>
                </div>
                <div class="bg-white rounded-b-2xl shadow-2xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($pedidosPreparando as $pedido)
                        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl p-6 shadow-lg">
                            <div class="text-center">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center text-white font-bold text-xl mx-auto mb-4 shadow-lg">
                                    {{ $pedido->numero_ticket }}
                                </div>
                                <div class="text-xl font-bold text-slate-800 mb-2">{{ $pedido->cliente_nombre }}</div>
                                
                                @php $items = json_decode($pedido->items, true); @endphp
                                @if($items && count($items) > 0)
                                    <div class="bg-white rounded-lg p-2 mb-3 border border-blue-200">
                                        <div class="text-xs font-medium text-slate-700 mb-1">Pedido:</div>
                                        @foreach($items as $item)
                                            <div class="text-xs text-slate-600 mb-1 p-1 bg-blue-50 rounded">
                                                <div class="flex justify-between items-start">
                                                    <span>{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</span>
                                                    <span class="text-blue-700 font-bold">Bs.{{ $item['precio'] ?? 0 }}</span>
                                                </div>
                                                @if(isset($item['acompanamiento']))
                                                    <div class="text-blue-600 font-medium mt-1">{{ ucfirst($item['acompanamiento']) }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="bg-blue-500 text-white px-3 py-1 rounded-full font-medium">
                                    Preparando...
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Pedidos Pendientes -->
            @php $pedidosPendientes = $pedidos->where('estado', 'pendiente') @endphp
            @if($pedidosPendientes->count() > 0)
            <div class="mb-8">
                <div class="bg-gradient-to-r from-orange-600 to-red-700 rounded-t-2xl p-6 text-white">
                    <h2 class="text-3xl font-bold flex items-center">
                        <i class="fas fa-clock mr-4"></i>
                        Pedidos Pendientes
                    </h2>
                </div>
                <div class="bg-white rounded-b-2xl shadow-2xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                        @foreach($pedidosPendientes as $pedido)
                        <div class="bg-gradient-to-br from-orange-50 to-red-50 border-2 border-orange-200 rounded-2xl p-4 shadow-lg">
                            <div class="text-center">
                                <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center text-white font-bold text-lg mx-auto mb-3 shadow-lg">
                                    {{ $pedido->numero_ticket }}
                                </div>
                                <div class="text-lg font-bold text-slate-800 mb-1">{{ $pedido->cliente_nombre }}</div>
                                
                                @php $items = json_decode($pedido->items, true); @endphp
                                @if($items && count($items) > 0)
                                    <div class="bg-white rounded-lg p-2 mb-2 border border-orange-200">
                                        @foreach($items as $item)
                                            <div class="text-xs text-slate-600 mb-1 p-1 bg-orange-50 rounded">
                                                <div class="flex justify-between items-start">
                                                    <span>{{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }}</span>
                                                    <span class="text-orange-700 font-bold">Bs.{{ $item['precio'] ?? 0 }}</span>
                                                </div>
                                                @if(isset($item['acompanamiento']))
                                                    <div class="text-orange-600 font-medium mt-1">{{ ucfirst($item['acompanamiento']) }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="bg-orange-500 text-white px-2 py-1 rounded-full text-sm font-medium">
                                    En cola
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-16">
                <div class="w-32 h-32 bg-gradient-to-br from-slate-600 to-slate-700 rounded-full flex items-center justify-center mx-auto mb-8">
                    <i class="fas fa-shopping-cart text-5xl text-white"></i>
                </div>
                <h3 class="text-3xl font-bold text-white mb-4">No hay pedidos activos</h3>
                <p class="text-xl text-slate-300">Los pedidos aparecerán aquí en tiempo real</p>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-900 mt-12 py-6">
        <div class="container mx-auto px-6 text-center text-slate-300">
            <div class="flex items-center justify-center space-x-4">
                <i class="fas fa-clock"></i>
                <span>Última actualización: {{ now()->format('d/m/Y H:i:s') }}</span>
            </div>
        </div>
    </div>
</body>
</html>
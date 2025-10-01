<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comprobante {{ $comprobante->numero_ticket }} - Restaurante El Chaqueño</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-8 text-white text-center">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-utensils text-2xl"></i>
                </div>
                <h1 class="text-2xl font-bold mb-2">Restaurante El Chaqueño</h1>
                <div class="text-emerald-100">Comprobante de Venta</div>
                <div class="text-3xl font-bold mt-4">{{ $comprobante->numero_ticket }}</div>
            </div>

            <!-- Información -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Cliente:</h3>
                        <div class="text-slate-700">
                            @if($comprobante->cliente_nombre === 'Venta Directa - Cajero')
                                <span class="text-green-600 font-medium">Venta Directa</span>
                            @else
                                {{ $comprobante->cliente_nombre }}
                            @endif
                        </div>
                        @if($comprobante->cliente_telefono !== 'N/A')
                            <div class="text-slate-600 text-sm">📞 {{ $comprobante->cliente_telefono }}</div>
                        @endif
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-slate-800 mb-3">Fecha:</h3>
                        <div class="text-slate-700">{{ \Carbon\Carbon::parse($comprobante->fecha_pedido)->format('d/m/Y H:i:s') }}</div>
                        <div class="text-slate-600 text-sm capitalize">Estado: {{ $comprobante->estado }}</div>
                    </div>
                </div>

                <!-- Productos -->
                <div class="mb-8">
                    <h3 class="font-semibold text-slate-800 mb-4">Productos:</h3>
                    
                    @php $items = json_decode($comprobante->items, true); @endphp
                    @if($items && count($items) > 0)
                        <div class="bg-slate-50 rounded-xl p-4">
                            @foreach($items as $item)
                            <div class="flex justify-between items-center py-3 border-b border-slate-200 last:border-b-0">
                                <div>
                                    <div class="font-medium text-slate-800">
                                        {{ $item['cantidad'] }} Costilla{{ $item['cantidad'] != 1 ? 's' : '' }} de Chancho
                                    </div>
                                    @if(isset($item['acompanamiento']))
                                        <div class="text-sm text-slate-600">Con {{ $item['acompanamiento'] }}</div>
                                    @endif
                                </div>
                                <div class="font-bold text-emerald-600">{{ number_format($item['precio'], 0) }} Bs</div>
                            </div>
                            @endforeach
                            
                            <div class="flex justify-between items-center pt-4 border-t-2 border-slate-300 mt-4">
                                <div class="text-lg font-bold text-slate-800">TOTAL:</div>
                                <div class="text-2xl font-bold text-emerald-600">{{ number_format($comprobante->total, 0) }} Bs</div>
                            </div>
                        </div>
                    @endif
                </div>

                @if($comprobante->notas && $comprobante->notas !== 'Venta realizada en caja')
                <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <h4 class="font-semibold text-yellow-800 mb-2">Notas:</h4>
                    <p class="text-yellow-700">{{ $comprobante->notas }}</p>
                </div>
                @endif

                <!-- Footer -->
                <div class="text-center pt-8 border-t border-slate-200">
                    <div class="text-slate-600 mb-4">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        Comprobante válido y verificado
                    </div>
                    <div class="text-sm text-slate-500">
                        Gracias por su preferencia<br>
                        Restaurante El Chaqueño
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
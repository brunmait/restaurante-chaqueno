@extends('layouts.cajero')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">
        <!-- Header del Comprobante -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-700 p-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Comprobante de Venta</h1>
                    <div class="text-purple-100">Restaurante El Chaqueño</div>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold">{{ $comprobante->numero_ticket }}</div>
                    <div class="text-purple-200">Ticket N°</div>
                </div>
            </div>
        </div>

        <!-- Información del Cliente -->
        <div class="p-8 border-b border-slate-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Información del Cliente</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-user text-slate-500 w-5"></i>
                            <span class="ml-3 text-slate-700">
                                @if($comprobante->cliente_nombre === 'Venta Directa - Cajero')
                                    <span class="text-green-600 font-medium">Venta Directa en Caja</span>
                                @else
                                    {{ $comprobante->cliente_nombre }}
                                @endif
                            </span>
                        </div>
                        @if($comprobante->cliente_telefono !== 'N/A')
                        <div class="flex items-center">
                            <i class="fas fa-phone text-slate-500 w-5"></i>
                            <span class="ml-3 text-slate-700">{{ $comprobante->cliente_telefono }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">Información de la Venta</h3>
                    <div class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-calendar text-slate-500 w-5"></i>
                            <span class="ml-3 text-slate-700">{{ \Carbon\Carbon::parse($comprobante->fecha_pedido)->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-tag text-slate-500 w-5"></i>
                            <span class="ml-3 text-slate-700 capitalize">{{ $comprobante->estado }}</span>
                        </div>
                        @if($comprobante->fecha_entrega)
                        <div class="flex items-center">
                            <i class="fas fa-check text-slate-500 w-5"></i>
                            <span class="ml-3 text-slate-700">Entregado: {{ \Carbon\Carbon::parse($comprobante->fecha_entrega)->format('d/m/Y H:i:s') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detalles de los Productos -->
        <div class="p-8">
            <h3 class="text-lg font-semibold text-slate-800 mb-6">Detalles de la Venta</h3>
            
            @php $items = json_decode($comprobante->items, true); @endphp
            @if($items && count($items) > 0)
                <div class="bg-slate-50 rounded-xl p-6">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th class="text-left py-3 text-slate-700 font-semibold">Producto</th>
                                <th class="text-center py-3 text-slate-700 font-semibold">Cantidad</th>
                                <th class="text-center py-3 text-slate-700 font-semibold">Acompañamiento</th>
                                <th class="text-right py-3 text-slate-700 font-semibold">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr class="border-b border-slate-100">
                                <td class="py-4 text-slate-800">Costillas de Chancho a la Cruz</td>
                                <td class="py-4 text-center text-slate-700">{{ $item['cantidad'] }}</td>
                                <td class="py-4 text-center text-slate-700 capitalize">
                                    @if(isset($item['acompanamiento']))
                                        {{ $item['acompanamiento'] }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="py-4 text-right font-bold text-emerald-600">{{ number_format($item['precio'], 0) }} Bs</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 border-slate-300">
                                <td colspan="3" class="py-4 text-right font-bold text-lg text-slate-800">TOTAL:</td>
                                <td class="py-4 text-right font-bold text-2xl text-emerald-600">{{ number_format($comprobante->total, 0) }} Bs</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endif

            @if($comprobante->notas)
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <h4 class="font-semibold text-yellow-800 mb-2">Notas:</h4>
                <p class="text-yellow-700">{{ $comprobante->notas }}</p>
            </div>
            @endif
        </div>

        <!-- Footer con QR -->
        <div class="bg-slate-50 p-8 border-t border-slate-200">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-600 mb-2">Código QR para verificación:</div>
                    <img src="https://chart.googleapis.com/chart?chs=128x128&cht=qr&chl={{ urlencode(route('comprobantes.public', $comprobante->numero_ticket)) }}" alt="QR Code" class="w-32 h-32 border border-slate-200 rounded-lg">
                </div>
                <div class="text-right">
                    <div class="text-sm text-slate-600 mb-4">Acciones:</div>
                    <div class="space-x-2">
                        <a href="{{ route('cashier.comprobantes.index') }}" 
                           class="bg-slate-600 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>Volver
                        </a>
                        <button onclick="window.print()" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-print mr-2"></i>Imprimir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    .max-w-4xl, .max-w-4xl * {
        visibility: visible;
    }
    .max-w-4xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>
@endsection
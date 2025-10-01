@extends('layouts.cajero')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 flex items-center">
            <i class="fas fa-receipt mr-3 text-emerald-600"></i>
            Comprobantes de Pago
        </h1>
        <p class="text-slate-600 mt-2">Historial de ventas y reimpresión de comprobantes</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('cashier.sales.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Nueva Venta</span>
        </a>
        <a href="{{ route('cashier.dashboard') }}" class="bg-slate-500 hover:bg-slate-600 text-white px-4 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>
    </div>
</div>

@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            {{ session('error') }}
        </div>
    </div>
@endif

@if (session('status'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            {{ session('status') }}
        </div>
    </div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-slate-200">
    <div class="border-b border-slate-200 p-6">
        <div class="flex items-center space-x-3">
            <div class="bg-emerald-100 p-2 rounded-lg">
                <i class="fas fa-file-invoice text-emerald-600"></i>
            </div>
            <h2 class="text-xl font-semibold text-slate-900">Comprobantes de esta Sesión</h2>
            @if(!empty($receipts))
                <span class="bg-emerald-100 text-emerald-800 px-2 py-1 rounded-full text-sm font-medium">{{ count($receipts) }} comprobantes</span>
            @endif
        </div>
    </div>
    
    <div class="p-6">
        @if(empty($receipts))
            <div class="text-center py-12">
                <div class="text-slate-500">
                    <i class="fas fa-receipt text-6xl mb-4"></i>
                    <h3 class="text-lg font-medium text-slate-900 mb-2">No hay comprobantes</h3>
                    <p class="text-slate-600">Los comprobantes de las ventas aparecerán aquí</p>
                    <a href="{{ route('cashier.sales.create') }}" class="inline-flex items-center mt-4 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Realizar Primera Venta
                    </a>
                </div>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-200">
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Fecha y Hora</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Comprobante</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Cliente</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Método</th>
                            <th class="text-left py-3 px-4 font-semibold text-slate-700">Productos</th>
                            <th class="text-right py-3 px-4 font-semibold text-slate-700">Total</th>
                            <th class="text-center py-3 px-4 font-semibold text-slate-700">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($receipts as $r)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="text-slate-900 font-medium">{{ \Carbon\Carbon::parse($r['fecha'])->format('d/m/Y') }}</div>
                                    <div class="text-slate-500 text-sm">{{ \Carbon\Carbon::parse($r['fecha'])->format('H:i') }}</div>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="font-mono text-slate-900 font-medium">{{ $r['ticket'] }}</div>
                                    <div class="text-slate-500 text-sm">{{ $r['platos'] ?? 0 }} platos</div>
                                </td>
                                <td class="py-4 px-4">
                                    @if($r['cliente'])
                                        <div class="text-slate-900 font-medium">{{ $r['cliente'] }}</div>
                                    @else
                                        <span class="text-slate-400 italic">Sin datos</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                        {{ $r['metodo'] ?? 'Efectivo' }}
                                    </span>
                                </td>
                                <td class="py-4 px-4">
                                    <div class="space-y-1">
                                        @foreach(array_slice($r['items'], 0, 2) as $it)
                                            <div class="text-sm text-slate-600">
                                                {{ $it['descripcion'] }} x{{ $it['cantidad'] }}
                                                <span class="text-slate-900 font-medium">Bs. {{ number_format($it['subtotal'], 0) }}</span>
                                            </div>
                                        @endforeach
                                        @if(count($r['items']) > 2)
                                            <div class="text-xs text-slate-500">+{{ count($r['items']) - 2 }} más...</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <div class="text-lg font-bold text-slate-900">Bs. {{ number_format($r['total'], 0) }}</div>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <div class="flex items-center justify-center space-x-2">
                                        <a href="{{ route('cashier.reprint', ['ticket'=>$r['ticket']]) }}" 
                                           class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-1"
                                           target="_blank">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>Ver PDF</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Resumen de la sesión -->
            <div class="mt-6 p-4 bg-slate-50 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                    <div>
                        <div class="text-2xl font-bold text-slate-900">{{ count($receipts) }}</div>
                        <div class="text-slate-600 text-sm">Comprobantes</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600">{{ array_sum(array_column($receipts, 'platos')) }}</div>
                        <div class="text-slate-600 text-sm">Platos Vendidos</div>
                    </div>
                    <div>
                        <div class="text-2xl font-bold text-emerald-600">Bs. {{ number_format(array_sum(array_column($receipts, 'total')), 0) }}</div>
                        <div class="text-slate-600 text-sm">Total Vendido</div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection




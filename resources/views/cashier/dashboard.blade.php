@extends('layouts.cajero')

@section('title', 'Panel de Cajero')

@section('content')
@php
    $user = auth()->user();
    $stock = \DB::table('stock_costillas')->first();
    $costoPromedio = $stock ? ($stock->costo_promedio ?? 0) : 0;
    $stockMinimo = $stock ? ($stock->stock_minimo ?? 10) : 10;
@endphp

<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Panel de Cajero</h1>
        <p class="text-gray-600 mt-1">Gestiona ventas y pedidos del restaurante</p>
    </div>
    <div class="flex items-center space-x-3">
        @if(($pedidosPendientes ?? 0) > 0)
            <span class="bg-red-100 text-red-800 px-3 py-2 rounded-full text-sm font-medium flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $pedidosPendientes }} pedidos pendientes
            </span>
        @else
            <span class="bg-green-100 text-green-800 px-3 py-2 rounded-full text-sm font-medium flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                Sin pedidos pendientes
            </span>
        @endif
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Datos del Cajero -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-6 py-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-id-badge text-white text-lg"></i>
                </div>
                <h3 class="text-white text-lg font-semibold ml-3">Datos del Cajero</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Nombre:</span>
                    <span class="font-medium text-gray-900">{{ $user->nombre ?? 'Cajero' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium text-gray-900">{{ $user->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Estado:</span>
                    @if(($user->activo ?? 1))
                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-circle text-green-500 mr-1"></i>Activo
                        </span>
                    @else
                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">
                            <i class="fas fa-circle text-gray-500 mr-1"></i>Inactivo
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Costillas -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-red-500 to-pink-600 px-6 py-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-bacon text-white text-lg"></i>
                </div>
                <h3 class="text-white text-lg font-semibold ml-3">Costillas Disponibles</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="text-center">
                <div class="text-4xl font-bold {{ $costillasDisponibles <= $stockMinimo ? 'text-red-600' : 'text-red-500' }} mb-2">{{ $costillasDisponibles }}</div>
                <div class="text-gray-600 text-sm mb-4">costillas en stock</div>
                @if($costillasDisponibles <= $stockMinimo)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                        <div class="text-red-700 font-semibold text-sm">⚠️ Stock Bajo</div>
                        <div class="text-red-600 text-xs">Mínimo: {{ $stockMinimo }}</div>
                    </div>
                @else
                    <div class="bg-green-50 rounded-lg p-3">
                        <div class="text-green-700 font-semibold text-sm">✓ Stock Normal</div>
                        <div class="text-green-600 text-xs">Costo: Bs. {{ number_format($costoPromedio, 2) }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Precios Disponibles -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-6 py-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-white text-lg"></i>
                </div>
                <h3 class="text-white text-lg font-semibold ml-3">Precios Disponibles</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="space-y-2">
                @foreach([50 => 1, 60 => 1.5, 70 => 2, 80 => 2.5, 90 => 3] as $precio => $cantidad)
                    @php $disponible = $costillasDisponibles >= $cantidad; @endphp
                    <div class="flex justify-between items-center p-3 rounded-lg {{ $disponible ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                        <div>
                            <div class="text-lg font-bold {{ $disponible ? 'text-green-700' : 'text-red-600' }}">Bs. {{ $precio }}</div>
                            <div class="text-xs {{ $disponible ? 'text-green-600' : 'text-red-500' }}">{{ $cantidad }} costilla{{ $cantidad > 1 ? 's' : '' }}</div>
                        </div>
                        <div class="text-xs {{ $disponible ? 'text-green-500' : 'text-red-400' }}">
                            {{ $disponible ? '✓ Disponible' : '❌ Sin stock' }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Pedidos en Línea -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Pedidos en Línea</h3>
                <p class="text-gray-600">Gestiona los pedidos realizados por los clientes</p>
                @if(($pedidosPendientes ?? 0) > 0)
                    <div class="mt-3 flex items-center text-red-600">
                        <i class="fas fa-clock mr-2"></i>
                        <span class="font-medium">{{ $pedidosPendientes }} pedidos esperando atención</span>
                    </div>
                @endif
            </div>
            <div class="text-right">
                <a href="#" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2">
                    <i class="fas fa-receipt"></i>
                    <span>Ver Pedidos</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Venta de Costillas -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 p-6">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Venta de Costillas</h3>
                <p class="text-gray-600">Sistema especializado para chancho a la cruz</p>
                <div class="mt-3 flex items-center {{ $costillasDisponibles > 0 ? 'text-green-600' : 'text-red-600' }}">
                    <i class="fas {{ $costillasDisponibles > 0 ? 'fa-check-circle' : 'fa-times-circle' }} mr-2"></i>
                    <span class="font-medium">{{ $costillasDisponibles > 0 ? 'Sistema activo' : 'Sin stock disponible' }}</span>
                </div>
            </div>
            <div class="text-right">
                <a href="{{ route('cashier.costillas.index') }}" class="bg-gradient-to-r from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center space-x-2 {{ $costillasDisponibles <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                    <i class="fas fa-bacon"></i>
                    <span>Vender Costillas</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estado Sin Pedidos -->
<div class="bg-white rounded-xl shadow-lg border border-slate-200">
    <div class="px-6 py-4 border-b border-slate-200">
        <h3 class="text-lg font-semibold text-gray-900">Estado del Sistema</h3>
    </div>
    <div class="p-8 text-center">
        <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-clipboard-list text-3xl text-slate-400"></i>
        </div>
        <h4 class="text-xl font-semibold text-slate-700 mb-2">Sistema Limpio</h4>
        <p class="text-slate-500 mb-4">No hay pedidos ni ventas registradas</p>
        <div class="grid grid-cols-2 gap-4 max-w-md mx-auto">
            <div class="bg-slate-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-slate-600">{{ $pedidosPendientes ?? 0 }}</div>
                <div class="text-sm text-slate-500">Pedidos</div>
            </div>
            <div class="bg-slate-50 rounded-lg p-3">
                <div class="text-2xl font-bold text-slate-600">{{ $ventasHoy ?? 0 }}</div>
                <div class="text-sm text-slate-500">Ventas Hoy</div>
            </div>
        </div>
        <p class="text-xs text-slate-400 mt-4">Listo para comenzar operaciones</p>
    </div>
</div>
@endsection



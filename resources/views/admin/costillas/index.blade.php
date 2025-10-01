@extends('layouts.admin')

@section('title', 'Gestión de Costillas')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Gestión de Costillas - Chancho a la Cruz</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Stock Actual -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Stock Actual</h2>
            
            <div class="bg-blue-50 rounded-lg p-4 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ number_format($stock->costillas_completas ?? 0, 1) }}</div>
                    <div class="text-sm text-gray-600">Carnes completas (18 costillas c/u)</div>
                </div>
                <div class="mt-4 text-center">
                    <div class="text-2xl font-semibold text-green-600">{{ number_format(($stock->costillas_completas ?? 0) * 18, 0) }}</div>
                    <div class="text-sm text-gray-600">Costillas individuales disponibles</div>
                </div>
            </div>

            <!-- Agregar Stock -->
            <form method="POST" action="{{ route('admin.costillas.agregar-stock') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Agregar Carnes Completas (18 costillas c/u)
                    </label>
                    <input type="number" step="0.1" min="0.1" name="cantidad" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                           placeholder="Ej: 2.5" required>
                </div>
                <button type="submit" 
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition-colors">
                    Agregar al Stock
                </button>
            </form>
        </div>

        <!-- Información del Sistema -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Precios de Venta</h2>
            
            <div class="space-y-3">
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">1 costilla</span>
                    <span class="text-green-600 font-bold">Bs. 50</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">1.5 costillas</span>
                    <span class="text-green-600 font-bold">Bs. 60</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">2 costillas</span>
                    <span class="text-green-600 font-bold">Bs. 70</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">2.5 costillas</span>
                    <span class="text-green-600 font-bold">Bs. 80</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-gray-50 rounded">
                    <span class="font-medium">3 costillas</span>
                    <span class="text-green-600 font-bold">Bs. 90</span>
                </div>
            </div>

            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <h3 class="font-semibold text-yellow-800 mb-2">Información del Costo</h3>
                <p class="text-sm text-yellow-700">
                    • Carne completa: Bs. 700 (18 costillas)<br>
                    • Costo por costilla: Bs. {{ number_format(700/18, 2) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Historial de Ventas -->
    <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">Últimas Ventas</h2>
        
        @if($ventas->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cantidad</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Precio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ventas as $venta)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($venta->created_at)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $venta->cliente_nombre ?: 'Cliente' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $venta->cantidad_costillas }} costillas
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                Bs. {{ number_format($venta->total, 0) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $venta->tipo === 'pedido_online' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                    {{ $venta->tipo === 'pedido_online' ? 'Online' : 'Directa' }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-center py-8">No hay ventas registradas</p>
        @endif
    </div>
</div>
@endsection
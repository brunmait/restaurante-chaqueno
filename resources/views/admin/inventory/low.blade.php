@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900">Stock Bajo</h1>
        <p class="text-slate-600 mt-2">Alertas de inventario mínimo</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.stock') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center space-x-2">
            <i class="fas fa-plus"></i>
            <span>Agregar Stock</span>
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Chancho -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-red-100 p-3 rounded-lg">
                        <i class="fas fa-drumstick-bite text-red-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Carne de Chancho</h3>
                </div>
                @if($chancho['stock_actual'] <= $minChancho)
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Crítico
                    </span>
                @else
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Normal
                    </span>
                @endif
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Stock Actual:</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($chancho['stock_actual'],1) }} kg</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Stock Mínimo:</span>
                    <span class="text-lg font-semibold text-slate-700">{{ number_format($minChancho,1) }} kg</span>
                </div>
                
                <!-- Barra de progreso -->
                <div class="w-full bg-slate-200 rounded-full h-3">
                    @php
                        $porcentaje = $minChancho > 0 ? min(100, ($chancho['stock_actual'] / $minChancho) * 100) : 0;
                        $colorBarra = $porcentaje <= 100 ? 'bg-red-500' : 'bg-green-500';
                    @endphp
                    <div class="{{ $colorBarra }} h-3 rounded-full transition-all duration-300" style="width: {{ $porcentaje }}%"></div>
                </div>
                
                @if($chancho['stock_actual'] <= $minChancho)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                            <span class="text-red-800 font-medium">¡Necesita reponer stock de chancho!</span>
                        </div>
                        <p class="text-red-700 text-sm mt-2">Faltan {{ number_format($minChancho - $chancho['stock_actual'], 1) }} kg para alcanzar el mínimo</p>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span class="text-green-800 font-medium">Nivel de stock adecuado</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Pollo -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="bg-yellow-100 p-3 rounded-lg">
                        <i class="fas fa-egg text-yellow-600 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900">Pollo Entero</h3>
                </div>
                @if($pollo['stock_actual'] <= $minPollo)
                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-exclamation-triangle mr-1"></i>Crítico
                    </span>
                @else
                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>Normal
                    </span>
                @endif
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Stock Actual:</span>
                    <span class="text-2xl font-bold text-slate-900">{{ number_format($pollo['stock_actual'],0) }} pollos</span>
                </div>
                
                <div class="flex justify-between items-center">
                    <span class="text-slate-600">Stock Mínimo:</span>
                    <span class="text-lg font-semibold text-slate-700">{{ number_format($minPollo,0) }} pollos</span>
                </div>
                
                <!-- Barra de progreso -->
                <div class="w-full bg-slate-200 rounded-full h-3">
                    @php
                        $porcentajePollo = $minPollo > 0 ? min(100, ($pollo['stock_actual'] / $minPollo) * 100) : 0;
                        $colorBarraPollo = $porcentajePollo <= 100 ? 'bg-red-500' : 'bg-green-500';
                    @endphp
                    <div class="{{ $colorBarraPollo }} h-3 rounded-full transition-all duration-300" style="width: {{ $porcentajePollo }}%"></div>
                </div>
                
                @if($pollo['stock_actual'] <= $minPollo)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-exclamation-triangle text-red-600"></i>
                            <span class="text-red-800 font-medium">¡Necesita reponer stock de pollo!</span>
                        </div>
                        <p class="text-red-700 text-sm mt-2">Faltan {{ number_format($minPollo - $pollo['stock_actual'], 0) }} pollos para alcanzar el mínimo</p>
                    </div>
                @else
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-check-circle text-green-600"></i>
                            <span class="text-green-800 font-medium">Nivel de stock adecuado</span>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

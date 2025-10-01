@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-6" x-data="comprasAdmin()">
    <!-- Header Elegante -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-slate-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-shopping-cart text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Gestión de Compras
                    </h1>
                    <p class="text-slate-600 text-lg">Sistema de registro de compras de costillas</p>
                </div>
            </div>
            <button class="bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                    data-bs-toggle="modal" data-bs-target="#modalNuevaCompra">
                <i class="fas fa-plus mr-3"></i>Nueva Compra
            </button>
        </div>
    </div>

    <!-- Alerta de Stock Bajo Elegante -->
    @if($alertaStock)
    <div class="bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-400 rounded-xl p-6 mb-8 shadow-lg" x-data="{ show: true }" x-show="show" x-transition>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-amber-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-amber-800 font-bold text-lg">⚠️ Stock Crítico</h3>
                    <p class="text-amber-700">Quedan solo <span class="font-bold">{{ $stockActual->costillas_disponibles }}</span> costillas. Stock mínimo: {{ $stockActual->stock_minimo }}</p>
                </div>
            </div>
            <button @click="show = false" class="text-amber-600 hover:text-amber-800 transition-colors">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Estadísticas Elegantes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stock Actual -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Stock Actual</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $stockActual->costillas_disponibles ?? 0 }}</p>
                    <p class="text-slate-600 text-sm">costillas disponibles</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-bacon text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Costo Promedio -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Costo Promedio</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ number_format($stockActual->costo_promedio ?? 0, 2) }}</p>
                    <p class="text-slate-600 text-sm">Bs. por costilla</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Compras Este Mes -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Compras del Mes</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $compras->where('fecha_compra', '>=', now()->startOfMonth())->count() }}</p>
                    <p class="text-slate-600 text-sm">compras realizadas</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-xl"></i>
                </div>
            </div>
        </div>
        
        <!-- Stock Mínimo -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-slate-200 hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Stock Mínimo</p>
                    <p class="text-3xl font-bold text-slate-800 mt-2">{{ $stockActual->stock_minimo ?? 10 }}</p>
                    <p class="text-slate-600 text-sm">nivel de alerta</p>
                </div>
                <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Historial de Compras Elegante -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-history mr-3"></i>
                Historial de Compras
            </h2>
        </div>
        
        <div class="p-8">
            @if($compras->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-slate-200">
                                <th class="text-left py-4 px-4 font-semibold text-slate-700">Fecha</th>
                                <th class="text-left py-4 px-4 font-semibold text-slate-700">Proveedor</th>
                                <th class="text-center py-4 px-4 font-semibold text-slate-700">Costillares</th>
                                <th class="text-center py-4 px-4 font-semibold text-slate-700">Costillas</th>
                                <th class="text-right py-4 px-4 font-semibold text-slate-700">Precio/Costillar</th>
                                <th class="text-right py-4 px-4 font-semibold text-slate-700">Costo/Costilla</th>
                                <th class="text-right py-4 px-4 font-semibold text-slate-700">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($compras as $compra)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-4">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar text-blue-600"></i>
                                        </div>
                                        <span class="font-medium text-slate-800">{{ $compra->fecha_compra->format('d/m/Y') }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $compra->proveedor->nombre }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="text-lg font-bold text-slate-800">{{ $compra->items->first()->cantidad ?? 0 }}</span>
                                </td>
                                <td class="py-4 px-4 text-center">
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-medium">
                                        {{ $compra->items->first()->costillas_totales ?? 0 }}
                                    </span>
                                </td>
                                <td class="py-4 px-4 text-right font-semibold text-slate-800">
                                    Bs. {{ number_format($compra->items->first()->precio_unitario ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-4 text-right font-semibold text-slate-600">
                                    Bs. {{ number_format($compra->items->first()->costo_por_costilla ?? 0, 2) }}
                                </td>
                                <td class="py-4 px-4 text-right">
                                    <span class="text-xl font-bold text-emerald-600">
                                        Bs. {{ number_format($compra->total, 2) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-shopping-cart text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-600 mb-2">No hay compras registradas</h3>
                    <p class="text-slate-500">Registra tu primera compra para comenzar</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Nueva Compra Elegante -->
    <div class="modal fade" id="modalNuevaCompra" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden">
                <form method="POST" action="{{ route('admin.compras.store') }}">
                    @csrf
                    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-plus-circle mr-3"></i>
                            Registrar Nueva Compra
                        </h2>
                        <p class="text-emerald-100 mt-2">Ingresa los detalles de la compra de costillas</p>
                    </div>
                    <div class="p-8 bg-slate-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Proveedor</label>
                                <div class="flex space-x-3">
                                    <select name="proveedor_id" class="flex-1 px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" required>
                                        <option value="">Seleccionar proveedor</option>
                                        @foreach($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <a href="{{ route('admin.proveedores.index') }}" class="px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-xl transition-all flex items-center" title="Gestionar Proveedores">
                                        <i class="fas fa-cog"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Fecha de Compra</label>
                                <input type="date" name="fecha_compra" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Cantidad de Costillares</label>
                                <input type="number" name="cantidad_costillares" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" min="1" x-model="form.cantidad" @input="calcularTotales()" required>
                                <p class="text-sm text-slate-500 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Cada costillar = 18 costillas
                                </p>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Precio por Costillar (Bs.)</label>
                                <input type="number" name="precio_por_costillar" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" step="0.01" min="0" x-model="form.precio" @input="calcularTotales()" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Observaciones</label>
                            <textarea name="observaciones" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all" rows="3" placeholder="Notas adicionales sobre la compra..."></textarea>
                        </div>
                        
                        <!-- Resumen de Cálculos Elegante -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6">
                            <h3 class="text-lg font-bold text-blue-800 flex items-center mb-4">
                                <i class="fas fa-calculator mr-3"></i>
                                Resumen de Compra
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white rounded-xl p-4 shadow-sm">
                                    <p class="text-sm text-slate-600 mb-1">Costillas Totales</p>
                                    <p class="text-2xl font-bold text-slate-800" x-text="form.cantidad * 18">0</p>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm">
                                    <p class="text-sm text-slate-600 mb-1">Costo por Costilla</p>
                                    <p class="text-2xl font-bold text-emerald-600">Bs. <span x-text="form.precio > 0 ? (form.precio / 18).toFixed(2) : '0.00'">0.00</span></p>
                                </div>
                                <div class="bg-white rounded-xl p-4 shadow-sm">
                                    <p class="text-sm text-slate-600 mb-1">Total a Pagar</p>
                                    <p class="text-2xl font-bold text-blue-600">Bs. <span x-text="(form.cantidad * form.precio).toFixed(2)">0.00</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white px-8 py-6 border-t border-slate-200 flex justify-end space-x-4">
                        <button type="button" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-all" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Registrar Compra
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeInUp {
    animation: fadeInUp 0.6s ease-out;
}

.hover-lift:hover {
    transform: translateY(-2px);
}
</style>

<script>
function comprasAdmin() {
    return {
        form: {
            cantidad: 1,
            precio: 700
        },
        
        calcularTotales() {
            // Los cálculos se muestran automáticamente con x-text
        }
    }
}
</script>
@endsection
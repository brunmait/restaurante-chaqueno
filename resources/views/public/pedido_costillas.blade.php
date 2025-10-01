@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8" x-data="pedidoForm()" x-init="loadStock()">
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Pedidos en Línea - Chancho a la Cruz</h1>
        <p class="text-gray-600">Selecciona tus costillas y realiza tu pedido</p>
    </div>

    <!-- Alerta de Stock -->
    <div x-show="stockInfo.costillas_disponibles <= 0" class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg mb-8 text-center">
        <i class="fas fa-times-circle text-3xl mb-3 text-red-600"></i>
        <p class="font-bold text-lg mb-2">❌ SIN STOCK DISPONIBLE</p>
        <p class="font-medium mb-1">No hay costillas disponibles en este momento</p>
        <p class="text-sm">Vuelve más tarde o contáctanos directamente al 63217872</p>
    </div>
    
    <div x-show="stockInfo.costillas_disponibles > 0 && stockInfo.costillas_disponibles <= 10" class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-6 py-4 rounded-lg mb-8 text-center">
        <i class="fas fa-exclamation-triangle text-2xl mb-2 text-yellow-600"></i>
        <p class="font-bold mb-1">⚠️ STOCK LIMITADO</p>
        <p class="text-sm">Quedan pocas costillas disponibles. ¡Haz tu pedido rápido!</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden" x-show="stockInfo.costillas_disponibles > 0">
        <form method="post" action="{{ route('public.placeOrder') }}" @submit="return validateForm()">
            @csrf
            
            <!-- Información del Cliente -->
            <div class="bg-red-600 text-white px-6 py-4">
                <h2 class="text-xl font-semibold flex items-center">
                    <i class="fas fa-user mr-3"></i>
                    Información del Cliente
                </h2>
            </div>
            
            <div class="p-6 border-b border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                        <input name="cliente_nombre" x-model="cliente.nombre"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                               placeholder="Tu nombre completo" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                        <input name="cliente_telefono" x-model="cliente.telefono"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                               placeholder="Ej: +591 70123456" required>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dirección (Opcional)</label>
                    <input name="cliente_direccion" x-model="cliente.direccion"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                           placeholder="Tu dirección para delivery">
                </div>
            </div>

            <!-- Menú de Costillas -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <img src="{{ asset('images/chancho-0000.jpg') }}" alt="Chancho a la Cruz" class="w-16 h-16 rounded-lg object-cover mr-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-bacon text-red-600 mr-3"></i>
                                Costillas - Chancho a la Cruz
                            </h2>
                            <p class="text-sm text-gray-600">Costillas asadas a la parrilla con sabor tradicional</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        Stock: <span x-text="stockInfo.costillas_disponibles">0</span> costillas
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="(precio, cantidad) in precios" :key="cantidad">
                        <div class="bg-gray-50 rounded-lg p-4 border-2 transition-colors"
                             :class="stockInfo.disponibilidad && stockInfo.disponibilidad[precio] ? 'border-transparent hover:border-red-200' : 'border-red-200 bg-red-50 opacity-60'">
                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <h3 class="font-semibold" :class="stockInfo.disponibilidad && stockInfo.disponibilidad[precio] ? 'text-gray-900' : 'text-red-600'" x-text="cantidad + (cantidad == 1 ? ' costilla' : ' costillas')"></h3>
                                    <p class="text-2xl font-bold" :class="stockInfo.disponibilidad && stockInfo.disponibilidad[precio] ? 'text-red-600' : 'text-red-500'" x-text="'Bs. ' + precio"></p>
                                    <p class="text-xs" :class="stockInfo.disponibilidad && stockInfo.disponibilidad[precio] ? 'text-gray-500' : 'text-red-500 font-semibold'">
                                        <span x-show="!stockInfo.disponibilidad || !stockInfo.disponibilidad[precio]">❌ Sin stock</span>
                                        <span x-show="stockInfo.disponibilidad && stockInfo.disponibilidad[precio]">✅ Disponible</span>
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Selector de acompañamiento -->
                            <div class="mb-3" x-show="stockInfo.disponibilidad && stockInfo.disponibilidad[precio]">
                                <label class="block text-xs font-medium text-gray-700 mb-1">Acompañamiento:</label>
                                <select x-model="acompanamientos[cantidad]" class="w-full text-sm border border-gray-300 rounded px-2 py-1">
                                    <option value="arroz">Con Arroz</option>
                                    <option value="mote">Con Mote</option>
                                    <option value="mixto">Mixto (Arroz + Mote)</option>
                                </select>
                            </div>
                            
                            <div class="flex items-center justify-center">
                                <button type="button" @click="agregarAlCarrito(cantidad, precio)" 
                                        :disabled="!stockInfo.disponibilidad || !stockInfo.disponibilidad[precio]"
                                        class="w-full py-2 px-4 rounded-lg font-medium transition-colors"
                                        :class="(stockInfo.disponibilidad && stockInfo.disponibilidad[precio]) ? 'bg-red-600 text-white hover:bg-red-700' : 'bg-gray-400 text-gray-600 cursor-not-allowed'">
                                    <i class="fas fa-plus mr-1"></i>Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Carrito de Compras -->
                <div class="mt-8" x-show="carrito.length > 0">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-shopping-cart text-red-600 mr-2"></i>
                        Tu Carrito (<span x-text="carrito.length"></span> platos)
                    </h3>
                    
                    <div class="space-y-3">
                        <template x-for="(item, index) in carrito" :key="index">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="font-medium text-gray-900" x-text="item.cantidad + (item.cantidad == 1 ? ' costilla' : ' costillas')"></div>
                                    <div class="text-sm text-gray-600" x-text="'Con ' + item.acompanamiento"></div>
                                    <div class="text-lg font-bold text-red-600" x-text="'Bs. ' + item.precio"></div>
                                </div>
                                <button type="button" @click="eliminarDelCarrito(index)" 
                                        class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Notas y Total -->
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas Especiales</label>
                    <textarea name="notas" x-model="cliente.notas" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" 
                              placeholder="Alguna indicación especial para tu pedido..."></textarea>
                </div>

                <!-- Total -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6" x-show="carrito.length > 0">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-semibold text-gray-900">Total del Pedido:</span>
                        <span class="text-3xl font-bold text-red-600" x-text="'Bs. ' + calcularTotal()">Bs. 0</span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        <span x-text="carrito.length + (carrito.length == 1 ? ' plato' : ' platos')"></span>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                <!-- Botones de Envío -->
                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button type="submit" x-show="carrito.length > 0"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Realizar Pedido</span>
                        </button>
                        <button type="button" onclick="window.location.href='{{ route('public.home') }}'"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                            <i class="fas fa-home"></i>
                            <span>Volver al Inicio</span>
                        </button>
                    </div>
                    <div class="text-center" x-show="carrito.length <= 0">
                        <p class="text-gray-500">Agrega platos a tu carrito para continuar</p>
                    </div>
                </div>

                <!-- Campos ocultos para el formulario -->
                <input type="hidden" name="items_json" x-model="JSON.stringify(carrito)">
                <input type="hidden" name="precio_total" x-model="calcularTotal()">
            </div>
        </form>
    </div>
</div>

<script>
function pedidoForm() {
    return {
        stockInfo: {
            costillas_disponibles: 0,
            disponibilidad: {}
        },
        precios: {
            1: 50,
            1.5: 60,
            2: 70,
            2.5: 80,
            3: 90
        },
        carrito: [],
        acompanamientos: {
            1: 'arroz',
            1.5: 'arroz',
            2: 'arroz',
            2.5: 'arroz',
            3: 'arroz'
        },
        cliente: {
            nombre: '',
            telefono: '',
            direccion: '',
            notas: ''
        },
        
        async loadStock() {
            try {
                const response = await fetch('{{ route("api.costillas.stock") }}');
                const data = await response.json();
                this.stockInfo = data;
            } catch (error) {
                console.error('Error cargando stock:', error);
            }
        },
        
        agregarAlCarrito(cantidad, precio) {
            if (this.stockInfo.disponibilidad && this.stockInfo.disponibilidad[precio]) {
                this.carrito.push({
                    cantidad: cantidad,
                    precio: precio,
                    acompanamiento: this.acompanamientos[cantidad]
                });
            }
        },
        
        eliminarDelCarrito(index) {
            this.carrito.splice(index, 1);
        },
        
        calcularTotal() {
            return this.carrito.reduce((total, item) => total + item.precio, 0);
        },
        
        validateForm() {
            if (this.carrito.length <= 0) {
                alert('Por favor agrega al menos un plato a tu carrito');
                return false;
            }
            if (!this.cliente.nombre || !this.cliente.telefono) {
                alert('Por favor completa tu nombre y teléfono');
                return false;
            }
            return true;
        }
    }
}
</script>
@endsection
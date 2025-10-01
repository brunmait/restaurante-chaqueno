@extends('layouts.cajero')

@section('title', 'Venta de Costillas')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="carritoVentas()">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Venta de Costillas - Chancho a la Cruz</h1>
        <a href="{{ route('cashier.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
            Volver al Dashboard
        </a>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Stock y Menú -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Stock Disponible -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-4 text-gray-800">Stock Disponible</h2>
                
                <div class="text-center">
                    <div class="text-4xl font-bold {{ $costillasDisponibles > 10 ? 'text-green-600' : ($costillasDisponibles > 0 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($costillasDisponibles, 0) }}
                    </div>
                    <div class="text-sm text-gray-600">Costillas disponibles</div>
                </div>

                @if($costillasDisponibles <= 0)
                    <div class="mt-4 p-3 bg-red-50 rounded-lg">
                        <p class="text-red-700 text-sm font-medium">⚠️ Sin stock disponible</p>
                    </div>
                @elseif($costillasDisponibles <= 10)
                    <div class="mt-4 p-3 bg-yellow-50 rounded-lg">
                        <p class="text-yellow-700 text-sm font-medium">⚠️ Stock bajo</p>
                    </div>
                @endif
            </div>

            <!-- Menú de Costillas -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold mb-6 text-gray-800">Seleccionar Platos</h2>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($precios as $precio => $cantidad)
                    @php $disponible = $costillasDisponibles >= floatval($cantidad); @endphp
                    <div class="border rounded-lg p-4 {{ $disponible ? 'border-gray-200 hover:border-blue-300' : 'border-gray-100 bg-gray-50 opacity-50' }}">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-semibold {{ $disponible ? 'text-gray-900' : 'text-gray-400' }}">
                                    {{ $cantidad }} {{ $cantidad == 1 ? 'Costilla' : 'Costillas' }}
                                </h3>
                                <div class="text-2xl font-bold {{ $disponible ? 'text-green-600' : 'text-gray-400' }}">
                                    Bs. {{ $precio }}
                                </div>
                            </div>
                            @if(!$disponible)
                                <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">Sin stock</span>
                            @endif
                        </div>
                        
                        @if($disponible)
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Acompañamiento:</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm" x-model="acompanamiento_{{ str_replace('.', '_', $cantidad) }}">
                                    <option value="arroz">Arroz</option>
                                    <option value="mote">Mote</option>
                                    <option value="mixto">Mixto (Arroz + Mote)</option>
                                </select>
                            </div>
                            
                            <button type="button" 
                                    @click="agregarAlCarrito('{{ $cantidad }}', {{ $precio }}, acompanamiento_{{ str_replace('.', '_', $cantidad) }})"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium transition-colors">
                                <i class="fas fa-plus mr-2"></i>Agregar al Pedido
                            </button>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Carrito de Compras -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Pedido Actual</h2>
            
            <!-- Cliente -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Cliente (Opcional):</label>
                <input type="text" x-model="cliente" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Nombre del cliente">
            </div>

            <!-- Items del Carrito -->
            <div class="space-y-3 mb-4" x-show="carrito.length > 0">
                <template x-for="(item, index) in carrito" :key="index">
                    <div class="bg-gray-50 rounded-lg p-3">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900" x-text="item.cantidad + (item.cantidad == 1 ? ' Costilla' : ' Costillas')"></div>
                                <div class="text-sm text-gray-600" x-text="'Acompañamiento: ' + item.acompanamiento"></div>
                                <div class="text-lg font-bold text-green-600" x-text="'Bs. ' + item.precio"></div>
                            </div>
                            <button type="button" @click="eliminarDelCarrito(index)" 
                                    class="text-red-500 hover:text-red-700 ml-2">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Mensaje si carrito vacío -->
            <div x-show="carrito.length === 0" class="text-center py-8 text-gray-500">
                <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                <p>No hay platos en el pedido</p>
                <p class="text-sm">Agrega platos desde el menú</p>
            </div>

            <!-- Total -->
            <div x-show="carrito.length > 0" class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-lg font-semibold">Total:</span>
                    <span class="text-2xl font-bold text-green-600" x-text="'Bs. ' + calcularTotal()"></span>
                </div>
                
                <div class="space-y-2">
                    <button type="button" @click="procesarVenta()" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-md font-medium">
                        <i class="fas fa-check mr-2"></i>Procesar Venta
                    </button>
                    <button type="button" @click="limpiarCarrito()" 
                            class="w-full bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-md">
                        <i class="fas fa-trash mr-2"></i>Limpiar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function carritoVentas() {
    return {
        carrito: [],
        cliente: '',
        @foreach($precios as $precio => $cantidad)
        acompanamiento_{{ str_replace('.', '_', $cantidad) }}: 'arroz',
        @endforeach
        
        agregarAlCarrito(cantidad, precio, acompanamiento) {
            this.carrito.push({
                cantidad: cantidad,
                precio: precio,
                acompanamiento: acompanamiento
            });
        },
        
        eliminarDelCarrito(index) {
            this.carrito.splice(index, 1);
        },
        
        limpiarCarrito() {
            this.carrito = [];
            this.cliente = '';
        },
        
        calcularTotal() {
            return this.carrito.reduce((total, item) => total + item.precio, 0);
        },
        
        async procesarVenta() {
            if (this.carrito.length === 0) {
                alert('Agrega al menos un plato al pedido');
                return;
            }
            
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('cliente_nombre', this.cliente);
            formData.append('items', JSON.stringify(this.carrito));
            
            try {
                const response = await fetch('{{ route("cashier.costillas.vender") }}', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Mostrar comprobante
                    this.mostrarComprobante(data);
                    
                    // Limpiar carrito
                    this.limpiarCarrito();
                } else {
                    alert(data.message || 'Error al procesar la venta');
                }
            } catch (error) {
                alert('Error de conexión');
            }
        },
        
        mostrarComprobante(data) {
            const comprobanteHtml = `
                <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="comprobanteModal">
                    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 max-h-screen overflow-y-auto">
                        <!-- Header -->
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 p-6 text-white text-center">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-utensils text-xl"></i>
                            </div>
                            <h2 class="text-xl font-bold mb-1">Restaurante El Chaqueño</h2>
                            <div class="text-emerald-100 text-sm">Comprobante de Venta</div>
                            <div class="text-2xl font-bold mt-3">${data.numero_ticket}</div>
                        </div>
                        
                        <!-- Contenido -->
                        <div class="p-6">
                            <!-- Info empresa -->
                            <div class="text-center mb-6 pb-4 border-b border-slate-200">
                                <div class="text-sm text-slate-600">NIT: 123456789</div>
                                <div class="text-sm text-slate-600">Dirección: Av. Principal #123</div>
                                <div class="text-sm text-slate-600">Teléfono: (591) 12345678</div>
                            </div>
                            
                            <!-- Info venta -->
                            <div class="mb-6">
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-600">Fecha:</span>
                                    <span>${new Date().toLocaleString('es-BO')}</span>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-slate-600">Cajero:</span>
                                    <span>{{ auth()->user()->nombre ?? auth()->user()->email }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-slate-600">Cliente:</span>
                                    <span>${this.cliente || 'Venta Directa'}</span>
                                </div>
                            </div>
                            
                            <!-- Productos -->
                            <div class="mb-6">
                                <h3 class="font-semibold mb-3 text-slate-800">Productos:</h3>
                                <div class="space-y-2">
                                    ${data.items.map(item => `
                                        <div class="flex justify-between text-sm py-2 border-b border-slate-100">
                                            <div>
                                                <div class="font-medium">${item.cantidad} Costilla${item.cantidad != 1 ? 's' : ''}</div>
                                                <div class="text-slate-600 text-xs">Con ${item.acompanamiento}</div>
                                            </div>
                                            <div class="font-bold text-emerald-600">${item.precio} Bs</div>
                                        </div>
                                    `).join('')}
                                </div>
                                
                                <div class="flex justify-between items-center pt-3 border-t-2 border-slate-300 mt-3">
                                    <span class="text-lg font-bold">TOTAL:</span>
                                    <span class="text-2xl font-bold text-emerald-600">${data.total} Bs</span>
                                </div>
                            </div>
                            
                            <!-- QR -->
                            <div class="text-center mb-6">
                                <div class="text-sm text-slate-600 mb-2">Código de verificación:</div>
                                <img src="https://chart.googleapis.com/chart?chs=120x120&cht=qr&chl=${encodeURIComponent(window.location.origin + '/comprobante/' + data.numero_ticket)}" 
                                     alt="QR Code" class="mx-auto border border-slate-200 rounded">
                            </div>
                            
                            <!-- Footer -->
                            <div class="text-center text-xs text-slate-500 mb-4">
                                <div>¡Gracias por su preferencia!</div>
                                <div>Conserve este comprobante</div>
                            </div>
                            
                            <!-- Botones -->
                            <div class="flex space-x-2">
                                <button onclick="window.print()" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                    <i class="fas fa-print mr-2"></i>Imprimir
                                </button>
                                <button onclick="document.getElementById('comprobanteModal').remove()" class="flex-1 bg-slate-600 hover:bg-slate-700 text-white py-2 px-4 rounded-lg text-sm font-medium">
                                    <i class="fas fa-times mr-2"></i>Cerrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', comprobanteHtml);
        }
    }
}
</script>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #comprobanteModal, #comprobanteModal * {
        visibility: visible;
    }
    #comprobanteModal {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        background: white !important;
    }
    #comprobanteModal .bg-black {
        background: white !important;
    }
    #comprobanteModal button {
        display: none !important;
    }
}
</style>
@endsection
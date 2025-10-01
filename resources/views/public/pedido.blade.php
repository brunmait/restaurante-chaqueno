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

            <!-- Menú de Chancho -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <img src="{{ asset('images/chancho-0000.jpg') }}" alt="Chancho a la Cruz" class="w-16 h-16 rounded-lg object-cover mr-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-bacon text-chaqueno-600 mr-3"></i>
                                Chancho a la Cruz
                            </h2>
                            <p class="text-sm text-gray-600">Carne asada a la parrilla con sabor tradicional</p>
                        </div>
                    </div>
                    <div class="text-sm text-gray-600">
                        Selecciona cantidad por precio
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach([50, 60, 70, 80, 90] as $precio)
                    @php $disponible = $platosPorPrecio[$precio] ?? 0; @endphp
                    <div class="bg-gray-50 rounded-lg p-4 border-2 {{ $disponible <= 0 ? 'border-red-200 bg-red-50' : 'border-transparent hover:border-green-200' }} transition-colors {{ $disponible <= 0 ? 'opacity-60' : '' }}">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-semibold {{ $disponible <= 0 ? 'text-red-600' : 'text-gray-900' }}">Chancho</h3>
                                <p class="text-2xl font-bold {{ $disponible <= 0 ? 'text-red-500' : 'text-green-600' }}">Bs. {{ $precio }}</p>
                                @if(isset($platosPorPrecio[$precio]))
                                    <p class="text-xs {{ $disponible <= 0 ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                                        @if($disponible <= 0)
                                            ❌ Sin stock
                                        @else
                                            Disponible: {{ $platosPorPrecio[$precio] }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <button type="button" @click="incrementar('chancho[{{ $precio }}]')" 
                                        class="{{ $disponible <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                        {{ $disponible <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" @click="decrementar('chancho[{{ $precio }}]')" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center"
                                    {{ $disponible <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" min="0" max="{{ $disponible }}" name="chancho[{{ $precio }}]" 
                                   x-model="cantidades.chancho{{ $precio }}" @input="updateTotal()"
                                   value="{{ old('chancho.'.$precio, 0) }}" 
                                   class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                   {{ $disponible <= 0 ? 'disabled readonly' : '' }}>
                            <button type="button" @click="incrementar('chancho[{{ $precio }}]')" 
                                    class="{{ $disponible <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-green-500 hover:bg-green-600' }} text-white w-8 h-8 rounded-full flex items-center justify-center"
                                    {{ $disponible <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Menú de Pollo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <img src="{{ asset('images/pollo.jpg') }}" alt="Pollo a la Leña" class="w-16 h-16 rounded-lg object-cover mr-4">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                                <i class="fas fa-drumstick-bite text-golden-500 mr-3"></i>
                                Pollo a la Leña
                            </h2>
                            <p class="text-sm text-gray-600">Pollo asado con leña, jugoso y sabroso</p>
                        </div>
                    </div>
                    @if(isset($polloUnidades))
                        <div class="text-sm text-gray-600">
                            Disponible: {{ $polloUnidades }} porciones
                        </div>
                    @endif
                </div>
                
                <div class="max-w-sm">
                    @php $polloDisponible = $polloUnidades ?? 0; @endphp
                    <div class="bg-gray-50 rounded-lg p-4 border-2 {{ $polloDisponible <= 0 ? 'border-red-200 bg-red-50 opacity-60' : 'border-transparent hover:border-yellow-200' }} transition-colors">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-semibold {{ $polloDisponible <= 0 ? 'text-red-600' : 'text-gray-900' }}">Pollo a la Leña</h3>
                                <p class="text-2xl font-bold {{ $polloDisponible <= 0 ? 'text-red-500' : 'text-yellow-600' }}">Bs. 60</p>
                                @if(isset($polloUnidades))
                                    <p class="text-xs {{ $polloDisponible <= 0 ? 'text-red-500 font-semibold' : 'text-gray-500' }}">
                                        @if($polloDisponible <= 0)
                                            ❌ Sin stock
                                        @else
                                            Disponible: {{ $polloUnidades }} porciones
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <button type="button" @click="incrementar('pollo[60]')" 
                                        class="{{ $polloDisponible <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                        {{ $polloDisponible <= 0 ? 'disabled' : '' }}>
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <button type="button" @click="decrementar('pollo[60]')" 
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-700 w-8 h-8 rounded-full flex items-center justify-center"
                                    {{ $polloDisponible <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-minus text-sm"></i>
                            </button>
                            <input type="number" min="0" max="{{ $polloDisponible }}" name="pollo[60]" 
                                   x-model="cantidades.pollo60" @input="updateTotal()"
                                   value="{{ old('pollo.60', 0) }}" 
                                   class="flex-1 text-center px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                                   {{ $polloDisponible <= 0 ? 'disabled readonly' : '' }}>
                            <button type="button" @click="incrementar('pollo[60]')" 
                                    class="{{ $polloDisponible <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-yellow-500 hover:bg-yellow-600' }} text-white w-8 h-8 rounded-full flex items-center justify-center"
                                    {{ $polloDisponible <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-plus text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notas y Total -->
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notas Especiales</label>
                    <textarea name="notas" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-chaqueno-500 focus:border-chaqueno-500" 
                              placeholder="Alguna indicación especial para tu pedido...">{{ old('notas') }}</textarea>
                </div>

                <!-- Total -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <div class="flex items-center justify-between">
                        <span class="text-xl font-semibold text-gray-900">Total del Pedido:</span>
                        <span class="text-3xl font-bold text-chaqueno-600" x-text="'Bs. ' + total.toFixed(2)">Bs. 0.00</span>
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
                    @if(isset($hayStock) && $hayStock)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <button type="submit" 
                                    class="bg-chaqueno-600 hover:bg-chaqueno-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-truck"></i>
                                <span>Pago Contra Entrega</span>
                            </button>
                            <button type="button" onclick="pagarConQR()" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors duration-200 flex items-center justify-center space-x-2">
                                <i class="fas fa-qrcode"></i>
                                <span>Pagar con QR</span>
                            </button>
                        </div>
                        <div class="text-center">
                            <div class="flex items-center justify-center text-gray-600">
                                <i class="fab fa-whatsapp text-chaqueno-500 mr-2"></i>
                                <span class="text-sm">Te contactaremos por WhatsApp</span>
                            </div>
                        </div>
                    @else
                        <div class="text-center">
                            <button type="button" disabled 
                                    class="bg-gray-400 text-white px-6 py-3 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center space-x-2 mx-auto">
                                <i class="fas fa-ban"></i>
                                <span>Sin Stock Disponible</span>
                            </button>
                            <p class="text-sm text-gray-500 mt-2">Contáctanos directamente al 63217872</p>
                        </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function pedidoForm() {
    return {
        cantidades: {
            chancho50: {{ old('chancho.50', 0) }},
            chancho60: {{ old('chancho.60', 0) }},
            chancho70: {{ old('chancho.70', 0) }},
            chancho80: {{ old('chancho.80', 0) }},
            chancho90: {{ old('chancho.90', 0) }},
            pollo60: {{ old('pollo.60', 0) }}
        },
        total: 0,
        
        incrementar(fieldName) {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input) {
                input.value = (parseInt(input.value) || 0) + 1;
                input.dispatchEvent(new Event('input'));
            }
        },
        
        decrementar(fieldName) {
            const input = document.querySelector(`[name="${fieldName}"]`);
            if (input && parseInt(input.value) > 0) {
                input.value = (parseInt(input.value) || 0) - 1;
                input.dispatchEvent(new Event('input'));
            }
        },
        
        updateTotal() {
            this.total = 0;
            this.total += 50 * (parseInt(this.cantidades.chancho50) || 0);
            this.total += 60 * (parseInt(this.cantidades.chancho60) || 0);
            this.total += 70 * (parseInt(this.cantidades.chancho70) || 0);
            this.total += 80 * (parseInt(this.cantidades.chancho80) || 0);
            this.total += 90 * (parseInt(this.cantidades.chancho90) || 0);
            this.total += 60 * (parseInt(this.cantidades.pollo60) || 0);
        }
    }
}

async function pagarConQR() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    // Calcular total manualmente
    let total = 0;
    total += 50 * (parseInt(formData.get('chancho[50]')) || 0);
    total += 60 * (parseInt(formData.get('chancho[60]')) || 0);
    total += 70 * (parseInt(formData.get('chancho[70]')) || 0);
    total += 80 * (parseInt(formData.get('chancho[80]')) || 0);
    total += 90 * (parseInt(formData.get('chancho[90]')) || 0);
    total += 60 * (parseInt(formData.get('pollo[60]')) || 0);
    
    if (total <= 0) {
        alert('Agrega al menos un producto a tu pedido');
        return;
    }
    
    // Validar datos requeridos
    const nombre = formData.get('cliente_nombre');
    const telefono = formData.get('cliente_telefono');
    if (!nombre || !telefono) {
        alert('Completa tu nombre y teléfono');
        return;
    }
    
    try {
        // Crear el pedido primero
        const pedidoResponse = await fetch('{{ route("public.placeOrder") }}', {
            method: 'POST',
            body: formData
        });
        
        if (pedidoResponse.ok) {
            // Generar QR de pago
            const pagoData = new FormData();
            pagoData.append('_token', '{{ csrf_token() }}');
            pagoData.append('concepto', `Pedido - ${nombre}`);
            pagoData.append('monto', total);
            
            const pagoResponse = await fetch('{{ route("pagos.crear") }}', {
                method: 'POST',
                body: pagoData
            });
            
            if (pagoResponse.ok) {
                window.open(pagoResponse.url, '_blank');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar el pedido');
    }
}
</script>
@endsection
@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">¡Pedido Recibido!</h1>
        <p class="text-gray-600">Pedido #{{ $pedido->id }}</p>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Información del Pedido -->
        <div class="bg-green-600 text-white px-6 py-4">
            <h2 class="text-xl font-semibold flex items-center">
                <i class="fas fa-receipt mr-3"></i>
                Detalles del Pedido
            </h2>
        </div>
        
        <div class="p-6 border-b border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Información del Cliente</h3>
                    <div class="space-y-2">
                        <p class="flex items-center text-gray-700">
                            <i class="fas fa-user w-5 mr-2 text-gray-400"></i>
                            <strong class="mr-2">Cliente:</strong> {{ $pedido->cliente_nombre }}
                        </p>
                        <p class="flex items-center text-gray-700">
                            <i class="fas fa-phone w-5 mr-2 text-gray-400"></i>
                            <strong class="mr-2">Teléfono:</strong> {{ $pedido->cliente_telefono }}
                        </p>
                        @if($pedido->cliente_direccion)
                        <p class="flex items-center text-gray-700">
                            <i class="fas fa-map-marker-alt w-5 mr-2 text-gray-400"></i>
                            <strong class="mr-2">Dirección:</strong> {{ $pedido->cliente_direccion }}
                        </p>
                        @endif
                    </div>
                </div>
                
                <div>
                    <h3 class="font-semibold text-gray-900 mb-3">Resumen del Pedido</h3>
                    @php
                        $items = $pedido->items_json ? json_decode($pedido->items_json, true) : [];
                    @endphp
                    @if($items)
                        <div class="space-y-2">
                            @foreach($items as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <div>
                                    <span class="font-medium">{{ ucfirst($item['tipo']) }}</span>
                                    <span class="text-gray-600">Bs. {{ $item['precio'] }}</span>
                                </div>
                                <div class="text-right">
                                    <span class="font-medium">x{{ $item['cantidad'] }}</span>
                                    <div class="text-sm text-gray-600">Bs. {{ number_format($item['precio'] * $item['cantidad'], 2) }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <div class="mt-4 pt-4 border-t-2 border-green-500">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-green-600">Bs. {{ number_format($pedido->monto, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Countdown Timer -->
        @if($pedido->expira_en)
        <div class="px-6 py-4 bg-yellow-50 border-b border-gray-200" id="countdownBox">
            <div class="flex items-center justify-center space-x-3">
                <i class="fas fa-clock text-yellow-600"></i>
                <span class="text-yellow-800">
                    Envía tu comprobante en los próximos 
                    <strong class="text-xl" id="countdown">--:--</strong> 
                    para asegurar tu reserva
                </span>
            </div>
        </div>
        @endif

        <!-- Instrucciones de Pago -->
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- QR Code -->
                <div class="text-center">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Escanea el QR para Pagar</h3>
                    <div class="bg-gray-50 rounded-lg p-6 inline-block">
                        <img src="{{ asset('images/qr_billetera.png') }}" alt="QR billetera" 
                             class="max-w-xs mx-auto rounded-lg border-2 border-gray-200"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                        <div style="display:none;" class="w-64 h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <div class="text-center text-gray-500">
                                <i class="fas fa-qrcode text-4xl mb-2"></i>
                                <p>QR no disponible</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm text-gray-600 mt-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Si tu banco genera código de referencia, inclúyelo en el mensaje
                    </p>
                </div>

                <!-- Instrucciones -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pasos para Completar tu Pedido</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">1</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Realiza el Pago</h4>
                                <p class="text-gray-600 text-sm">Escanea el QR con tu app bancaria y transfiere el monto total</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Envía el Comprobante</h4>
                                <p class="text-gray-600 text-sm">Toma captura del comprobante y envíalo por WhatsApp</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-3">
                            <div class="bg-green-600 text-white w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                            <div>
                                <h4 class="font-medium text-gray-900">Confirmación</h4>
                                <p class="text-gray-600 text-sm">Te confirmaremos tu pedido y tiempo de preparación</p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="mt-8 space-y-3">
                        @if($pedido->whatsapp_link)
                        <a href="{{ $pedido->whatsapp_link }}" target="_blank" 
                           class="w-full bg-green-500 hover:bg-green-600 text-white py-3 px-6 rounded-lg font-semibold flex items-center justify-center space-x-2 transition-colors duration-200" 
                           id="btnWhatsapp">
                            <i class="fab fa-whatsapp text-xl"></i>
                            <span>Enviar Comprobante por WhatsApp</span>
                        </a>
                        @endif
                        
                        <a href="{{ route('public.home') }}" 
                           class="w-full bg-gray-100 hover:bg-gray-200 text-gray-700 py-3 px-6 rounded-lg font-medium flex items-center justify-center space-x-2 transition-colors duration-200">
                            <i class="fas fa-home"></i>
                            <span>Volver al Inicio</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($pedido->expira_en)
<script>
(function(){
    var end = new Date("{{ \Carbon\Carbon::parse($pedido->expira_en)->format('Y-m-d H:i:s') }}").getTime();
    var el = document.getElementById('countdown');
    var box = document.getElementById('countdownBox');
    var btn = document.getElementById('btnWhatsapp');
    
    function tick(){
        var now = new Date().getTime();
        var diff = end - now;
        
        if (diff <= 0){
            if (box) {
                box.className = 'px-6 py-4 bg-red-50 border-b border-gray-200';
                box.innerHTML = '<div class="flex items-center justify-center space-x-3"><i class="fas fa-exclamation-triangle text-red-600"></i><span class="text-red-800 font-medium">Tiempo expirado. Contacta directamente para confirmar tu pedido.</span></div>';
            }
            if (btn) btn.classList.add('opacity-50', 'cursor-not-allowed');
            return;
        }
        
        var m = Math.floor(diff/60000);
        var s = Math.floor((diff%60000)/1000);
        if (el) el.textContent = String(m).padStart(2,'0')+':'+String(s).padStart(2,'0');
        setTimeout(tick, 500);
    }
    tick();
})();
</script>
@endif
@endsection
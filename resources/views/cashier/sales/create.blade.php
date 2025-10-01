@extends('layouts.cajero')

@section('title', 'Registrar Venta')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-shopping-cart mr-3 text-chaqueno-600"></i>
            Nueva Venta
        </h1>
        <p class="text-gray-600 mt-1">Registra una venta directa en el local</p>
    </div>
    <div class="flex items-center space-x-3">
        <span class="bg-chaqueno-100 text-chaqueno-800 px-3 py-2 rounded-full text-sm font-medium">
            Cajero: {{ auth()->user()->nombre ?? 'Usuario' }}
        </span>
        <a href="{{ route('cashier.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>
    </div>
</div>

@php
    // Cálculo de stock de chancho en kg
    $chanchoId = (int) \App\Models\Setting::get('producto_id_chancho', 0);
    $stockChanchoKg = $chanchoId
        ? \DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'entrada')->sum('cantidad')
          - \DB::table('stock')->where('producto_id', $chanchoId)->where('tipo', 'salida')->sum('cantidad')
        : 0;

    $pesoCostillaKg = (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2); 
    $stockKilos = $stockChanchoKg ?? 0;
    $costillasDisponibles = $pesoCostillaKg > 0 ? floor($stockKilos / $pesoCostillaKg) : 0;

    $platosPorCostillaMap = [50 => 16, 60 => 10, 70 => 8, 80 => 6, 90 => 4];
    $factorCostillas = ($pesoCostillaKg > 0) ? ($stockKilos / $pesoCostillaKg) : 0;
    $platosPorPrecio = [];
    foreach ($platosPorCostillaMap as $precio => $platosPorCostilla) {
        $platosPorPrecio[$precio] = (int) floor($factorCostillas * $platosPorCostilla);
    }
    $hayPlatos = array_sum($platosPorPrecio) > 0;
    $costillasDisponibles = $hayPlatos ? max(1, (int) floor($factorCostillas)) : 0;
    
    // Stock de pollo
    $polloId = (int) \App\Models\Setting::get('producto_id_pollo', 0);
    $porcionesPorPollo = (int) (\App\Models\Setting::get('porciones_por_pollo', 2));
    $stockPolloUn = $polloId
        ? \DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'entrada')->sum('cantidad')
          - \DB::table('stock')->where('producto_id', $polloId)->where('tipo', 'salida')->sum('cantidad')
        : 0;
    $porcionesPolloPosibles = $porcionesPorPollo > 0 ? floor($stockPolloUn * $porcionesPorPollo) : 0;
@endphp

<!-- Stock Info Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Chancho Stock -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 px-6 py-4">
            <div class="flex items-center">
                <span class="text-white text-2xl mr-3">🥩</span>
                <h3 class="text-white text-lg font-semibold">Chancho Disponible</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="text-2xl font-bold text-orange-600">{{ $costillasDisponibles }} costillas</div>
                <div class="text-sm text-gray-500">(≈{{ $pesoCostillaKg }} kg c/u)</div>
            </div>
            <div class="space-y-2">
                @foreach ($platosPorPrecio as $precio => $cantidad)
                    <div class="flex justify-between items-center bg-orange-50 rounded px-3 py-2">
                        <span class="text-sm text-gray-700">Platos Bs. {{ $precio }}</span>
                        <span class="font-semibold text-orange-700">{{ $cantidad }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Pollo Stock -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4">
            <div class="flex items-center">
                <span class="text-white text-2xl mr-3">🐔</span>
                <h3 class="text-white text-lg font-semibold">Pollo Disponible</h3>
            </div>
        </div>
        <div class="p-6">
            <div class="text-center mb-4">
                <div class="text-2xl font-bold text-yellow-600">{{ number_format($stockPolloUn, 0) }} pollos</div>
                <div class="text-sm text-gray-500">≈ {{ $porcionesPolloPosibles }} porciones</div>
            </div>
            <div class="bg-yellow-50 rounded-lg p-3 text-center">
                <div class="text-sm text-yellow-700">{{ $porcionesPorPollo }} porciones por pollo</div>
            </div>
        </div>
    </div>
</div>

<!-- Info Notes -->
<div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-8">
    <div class="flex items-start">
        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
        <div class="text-sm text-blue-800">
            <p class="mb-2"><strong>Nota:</strong> Pollo se descuenta en unidades; cada unidad consume 1/{{ max(1,$porcionesPorPollo) }} de un pollo.</p>
            <p>Chancho se descuenta en kg según costillitas: 1 costillita ≈ 0.75 kg (1 costilla grande ≈ 6 kg = 8 costillitas).</p>
        </div>
    </div>
</div>


<!-- Alerts -->
@if(isset($alertaStock) && count($alertaStock) > 0)
    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-yellow-500 mr-3"></i>
            <div class="text-yellow-800 text-sm">
                <p class="font-semibold mb-2">⚠️ ALERTA DE STOCK BAJO:</p>
                @foreach($alertaStock as $alerta)
                    <p class="mb-1">• {{ $alerta }}</p>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if(!$hayPlatos)
    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-times-circle text-red-500 mr-3"></i>
            <div class="text-red-800 text-sm">
                <p class="font-semibold mb-2">❌ SIN STOCK DISPONIBLE</p>
                <p>No hay carne suficiente para realizar ventas. Contacta al administrador para reabastecer.</p>
            </div>
        </div>
    </div>
@else
    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
        <div class="flex items-center">
            <i class="fas fa-check-circle text-chaqueno-500 mr-3"></i>
            <p class="text-chaqueno-800 text-sm">Ingresa múltiples productos, calcula el total automáticamente y registra el pago.</p>
        </div>
    </div>
@endif

<div id="stockWarn" class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 hidden">
    <div class="flex items-center">
        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
        <p class="text-red-800 text-sm" id="stockWarnText"></p>
    </div>
</div>

<form id="saleForm" method="POST" action="{{ route('cashier.sales.checkout') }}">@csrf
    <!-- Información del Cliente -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 rounded-t-xl">
            <h3 class="text-white text-lg font-semibold flex items-center">
                <i class="fas fa-user mr-3"></i>
                Información del Cliente
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-blue-600"></i>
                        Nombre del Cliente
                    </label>
                    <input type="text" name="cliente_nombre" placeholder="Ej: Juan Pérez" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Aparecerá en el comprobante de pago</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-blue-600"></i>
                        Teléfono del Cliente
                    </label>
                    <input type="tel" name="cliente_telefono" placeholder="Ej: 63217872" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors">
                    <p class="text-xs text-gray-500 mt-1">Para contacto y seguimiento</p>
                </div>
            </div>
            <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                    <div class="text-sm text-blue-800">
                        <strong>Información del comprobante:</strong> Los datos del cliente aparecerán en el comprobante de pago junto con la información del restaurante (Rincón Chaqueño, Tel: 63217872, El Alto - Bolivia).
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-8">
        <div class="bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 px-6 py-4 rounded-t-xl">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <h3 class="text-white text-lg font-semibold flex items-center mb-4 lg:mb-0">
                    <i class="fas fa-shopping-basket mr-3"></i>
                    Productos
                </h3>
                <div class="flex flex-wrap gap-2">
                    @if($hayPlatos)
                        <button type="button" onclick="addQuick('chancho',50,1)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $platosPorPrecio[50] <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Chancho Bs. 50 @if($platosPorPrecio[50] <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addQuick('chancho',60,2)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $platosPorPrecio[60] <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Chancho Bs. 60 @if($platosPorPrecio[60] <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addQuick('chancho',70,2.5)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $platosPorPrecio[70] <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Chancho Bs. 70 @if($platosPorPrecio[70] <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addQuick('chancho',80,3)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $platosPorPrecio[80] <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Chancho Bs. 80 @if($platosPorPrecio[80] <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addQuick('chancho',90,3.5)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $platosPorPrecio[90] <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Chancho Bs. 90 @if($platosPorPrecio[90] <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addQuick('pollo',60,1)" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition-colors" {{ $porcionesPolloPosibles <= 0 ? 'disabled title="Sin stock"' : '' }}>
                            Pollo Bs. 60 @if($porcionesPolloPosibles <= 0)<span class="text-red-200">(Sin stock)</span>@endif
                        </button>
                        <button type="button" onclick="addRow()" class="bg-white text-green-700 px-4 py-1.5 rounded-lg text-xs font-medium hover:bg-gray-100 transition-colors">
                            <i class="fas fa-plus mr-1"></i>Agregar
                        </button>
                    @else
                        <div class="bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-ban mr-2"></i>Sin productos disponibles - Stock agotado
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="p-6">
            <div id="items" class="space-y-4"></div>
            <template id="itemTemplate">
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                    <div class="grid grid-cols-1 md:grid-cols-7 gap-4 items-end">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Producto</label>
                            <select class="item-producto w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" required>
                                <option value="">Seleccionar</option>
                                <option value="chancho">Chancho</option>
                                <option value="pollo">Pollo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Variante</label>
                            <select class="item-variante w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="none">Sin variante</option>
                                <option value="1">1 costilla</option>
                                <option value="2">2 costillas</option>
                                <option value="2.5">2.5 costillas</option>
                                <option value="3">3 costillas</option>
                                <option value="3.5">3.5 costillas</option>
                                <option value="pollo">1 porción pollo</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Acompañamiento</label>
                            <select class="item-porcion w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                <option value="">-</option>
                                <option value="arroz">Arroz</option>
                                <option value="mote">Mote</option>
                                <option value="mixto">Mixto</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cantidad</label>
                            <input type="number" class="item-cantidad w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="1" min="1">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Precio</label>
                            <input type="number" step="0.01" class="item-precio w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" value="0">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Subtotal</label>
                            <input type="text" class="item-subtotal w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg" value="0.00" readonly>
                        </div>
                        <div>
                            <button type="button" class="item-remove w-full bg-red-600 hover:bg-red-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" class="item-tipo" value="">
                </div>
            </template>
        </div>
    </div>

    <!-- Resumen y Pago -->
    <div class="bg-white rounded-xl shadow-lg border border-slate-200 mb-8">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-6 py-4 rounded-t-xl">
            <h3 class="text-white text-lg font-semibold flex items-center">
                <i class="fas fa-calculator mr-3"></i>
                Resumen y Pago
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Payment Method -->
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                        <select name="metodo_pago" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" onchange="togglePagoQR(this.value)">
                            <option value="">Seleccionar método</option>
                            <option value="Efectivo">Efectivo</option>
                            <option value="Tarjeta">Tarjeta</option>
                            <option value="Transferencia">Transferencia</option>
                            <option value="QR">QR</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                        <textarea rows="4" name="observaciones" placeholder="Observaciones adicionales..." 
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                    </div>
                </div>

                <!-- Payment Summary -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Subtotal:</span>
                            <span class="text-xl font-semibold text-gray-900" id="subtotalTxt">Bs. 0.00</span>
                        </div>
                        <div class="border-t border-green-300 pt-4">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-900">Total:</span>
                                <span class="text-2xl font-bold text-green-600" id="totalTxt">Bs. 0.00</span>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 pt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pagó:</label>
                                <input type="number" step="0.01" id="pagoInput" name="monto_pagado" value="0" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Cambio:</label>
                                <input type="text" id="cambioInput" value="0.00" readonly 
                                       class="w-full px-3 py-2 bg-gray-100 border border-gray-300 rounded-lg">
                            </div>
                        </div>
                        
                        <input type="hidden" name="items_json" id="itemsJson">
                        
                        <div id="botonesNormales">
                            @if($hayPlatos)
                                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white py-4 px-6 rounded-xl font-semibold text-lg transition-all duration-200 flex items-center justify-center space-x-2 mt-6">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Finalizar Venta</span>
                                </button>
                            @else
                                <button type="button" disabled class="w-full bg-gray-400 text-white py-4 px-6 rounded-xl font-semibold text-lg flex items-center justify-center space-x-2 mt-6 cursor-not-allowed">
                                    <i class="fas fa-ban"></i>
                                    <span>Sin Stock Disponible</span>
                                </button>
                            @endif
                        </div>
                        
                        <div id="botonesQR" class="hidden">
                            @if($hayPlatos)
                                <button type="button" onclick="generarQR()" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white py-4 px-6 rounded-xl font-semibold text-lg transition-all duration-200 flex items-center justify-center space-x-2 mt-6">
                                    <i class="fas fa-qrcode"></i>
                                    <span>Generar QR de Pago</span>
                                </button>
                            @else
                                <button type="button" disabled class="w-full bg-gray-400 text-white py-4 px-6 rounded-xl font-semibold text-lg flex items-center justify-center space-x-2 mt-6 cursor-not-allowed">
                                    <i class="fas fa-ban"></i>
                                    <span>Sin Stock Disponible</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
const preciosPorVariante = {
    chancho: { '1': 50, '2': 60, '2.5': 70, '3': 80, '3.5': 90 },
    pollo: { 'pollo': 60 }
};

function addRow(prefill) {
    const tpl = document.getElementById('itemTemplate').content.cloneNode(true);
    const row = tpl.querySelector('div');
    row.querySelector('.item-remove').addEventListener('click', () => { row.remove(); recalc(); });
    const prod = row.querySelector('.item-producto');
    const variante = row.querySelector('.item-variante');
    const cantidad = row.querySelector('.item-cantidad');
    const precio = row.querySelector('.item-precio');
    const subtotal = row.querySelector('.item-subtotal');
    const tipoHidden = row.querySelector('.item-tipo');
    const porcionSel = row.querySelector('.item-porcion');

    function updatePrecio() {
        const tipo = prod.value;
        tipoHidden.value = tipo;
        if (tipo && preciosPorVariante[tipo]) {
            const key = (tipo === 'pollo') ? 'pollo' : variante.value;
            if (preciosPorVariante[tipo][key] !== undefined) {
                precio.value = preciosPorVariante[tipo][key];
            }
        }
        updateSubtotal();
    }

    function updateSubtotal() {
        const st = (parseFloat(precio.value || 0) * parseFloat(cantidad.value || 0));
        subtotal.value = st.toFixed(2);
        recalc();
    }

    prod.addEventListener('change', updatePrecio);
    variante.addEventListener('change', updatePrecio);
    cantidad.addEventListener('input', updateSubtotal);
    precio.addEventListener('input', updateSubtotal);

    document.getElementById('items').appendChild(row);
    if (prefill) {
        prod.value = prefill.tipo;
        variante.value = prefill.variante;
        updatePrecio();
        cantidad.value = prefill.cantidad || 1;
        updateSubtotal();
    }
}

function addQuick(tipo, precio, varianteVal) {
    addRow({ tipo, variante: (tipo === 'pollo') ? 'pollo' : String(varianteVal), cantidad: 1 });
}

function recalc() {
    let total = 0;
    const items = [];
    document.querySelectorAll('#items > div').forEach((row) => {
        const tipo = row.querySelector('.item-tipo').value;
        const prod = row.querySelector('.item-producto').value;
        const variante = row.querySelector('.item-variante').value;
        const cantidad = parseFloat(row.querySelector('.item-cantidad').value || 0);
        const precio = parseFloat(row.querySelector('.item-precio').value || 0);
        const subtotal = parseFloat(row.querySelector('.item-subtotal').value || 0);
        const tipoHidden = row.querySelector('.item-tipo');
        const porcionSel = row.querySelector('.item-porcion');
        if (prod && cantidad > 0 && precio >= 0) {
            const porcion = (porcionSel && porcionSel.value) ? porcionSel.value : '';
            items.push({ tipo, producto: prod, variante, cantidad, precio, subtotal, porcion });
            total += subtotal;
        }
    });
    document.getElementById('subtotalTxt').textContent = `Bs. ${total.toFixed(2)}`;
    document.getElementById('totalTxt').textContent = `Bs. ${total.toFixed(2)}`;
    const pago = parseFloat(document.getElementById('pagoInput').value || 0);
    document.getElementById('cambioInput').value = (pago - total).toFixed(2);
    // Validación de stock de chancho antes de permitir enviar
    const stockKg = parseFloat(({{ json_encode($stockKilos) }}) || 0);
    const pesoCostilla = parseFloat(({{ json_encode($pesoCostillaKg) }}) || 0.000001);
    const kilosPorPlato = {
        50: (pesoCostilla / 16),
        60: (pesoCostilla / 10),
        70: (pesoCostilla / 8),
        80: (pesoCostilla / 6),
        90: (pesoCostilla / 4),
    };
    let kilosNecesariosChancho = 0;
    items.forEach((it) => {
        if (it.producto === 'chancho') {
            const kpp = kilosPorPlato[it.precio] || 0;
            kilosNecesariosChancho += kpp * (parseFloat(it.cantidad)||0);
        }
    });
    const faltaKg = kilosNecesariosChancho - stockKg;
    const warnEl = document.getElementById('stockWarn');
    const warnText = document.getElementById('stockWarnText');
    if (warnEl && warnText) {
        if (faltaKg > 0.0001) {
            warnText.textContent = `Carne insuficiente: faltan ${faltaKg.toFixed(2)} kg para completar esta venta.`;
            warnEl.classList.remove('hidden');
        } else {
            warnEl.classList.add('hidden');
            warnText.textContent = '';
        }
    }
    document.getElementById('itemsJson').value = JSON.stringify(items);
}

document.getElementById('pagoInput').addEventListener('input', recalc);

function togglePagoQR(metodo) {
    const normales = document.getElementById('botonesNormales');
    const qr = document.getElementById('botonesQR');
    
    if (metodo === 'QR') {
        normales.classList.add('hidden');
        qr.classList.remove('hidden');
    } else {
        normales.classList.remove('hidden');
        qr.classList.add('hidden');
    }
}

async function generarQR() {
    const items = JSON.parse(document.getElementById('itemsJson').value || '[]');
    if (items.length === 0) {
        alert('Agrega al menos un producto');
        return;
    }
    
    const total = items.reduce((sum, item) => sum + (item.subtotal || 0), 0);
    const clienteNombre = document.querySelector('[name="cliente_nombre"]').value || 'Cliente';
    
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('concepto', `Venta - ${clienteNombre}`);
    formData.append('monto', total);
    
    try {
        const response = await fetch('{{ route("pagos.crear") }}', {
            method: 'POST',
            body: formData
        });
        
        if (response.ok) {
            window.open(response.url, '_blank');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al generar QR');
    }
}

// fila inicial
addRow();

// Interceptar submit si no alcanza el stock de chancho
document.getElementById('saleForm').addEventListener('submit', function(e){
    const items = JSON.parse(document.getElementById('itemsJson').value || '[]');
    const stockKg = parseFloat(({{ json_encode($stockKilos) }}) || 0);
    const pesoCostilla = parseFloat(({{ json_encode($pesoCostillaKg) }}) || 0.000001);
    const kilosPorPlato = {50:(pesoCostilla/16),60:(pesoCostilla/10),70:(pesoCostilla/8),80:(pesoCostilla/6),90:(pesoCostilla/4)};
    let kilosNecesariosChancho = 0;
    items.forEach((it) => {
        if (it.producto === 'chancho') {
            const kpp = kilosPorPlato[it.precio] || 0;
            kilosNecesariosChancho += kpp * (parseFloat(it.cantidad)||0);
        }
    });
    if (kilosNecesariosChancho - stockKg > 0.0001) {
        e.preventDefault();
        alert('Carne insuficiente: no se puede realizar la venta con el stock actual.');
    }
});
</script>
@endsection

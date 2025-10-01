@extends('layouts.admin')

@section('content')
<h3 class="text-2xl font-bold text-gray-900 mb-4">Disponibilidad de Stock</h3>

@if (session('status'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
        {{ session('status') }}
    </div>
@endif

<div class="bg-white rounded-lg shadow p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h5 class="text-lg font-semibold mb-2">Resumen de carne de chancho</h5>
            <p class="mb-1">Kilos en stock: <strong>{{ number_format($kilos, 2) }} kg</strong></p>
            <p class="mb-1">Mínimo configurado: <strong>{{ number_format($minKilos, 2) }} kg</strong></p>
            @if($alerta)
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-3 py-2 rounded mt-2">
                    Alerta: El stock está por debajo del mínimo.
                </div>
            @endif
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h5 class="text-lg font-semibold">📥 Entradas de Stock (Compras)</h5>
        <div class="space-x-2">
            <a class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm" href="{{ route('admin.productos.index') }}">Ir a configuración de stock</a>
            <form action="{{ route('admin.inventory.reset') }}" method="post" class="inline" onsubmit="return confirm('¿Seguro que quieres vaciar todo el inventario? Esta acción borrará todos los movimientos y pondrá el stock en 0.');">
                @csrf
                <button class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-2 rounded text-sm">Vaciar inventario</button>
            </form>
        </div>
    </div>

    <!-- Entradas de Chancho -->
    <div class="border border-red-200 rounded-lg mb-4">
        <div class="bg-red-500 text-white px-4 py-2 rounded-t-lg">
            <h6 class="font-semibold">🥩 Carne de Chancho</h6>
        </div>
        <div class="p-4">
            @php
                $chanchoId = (int) \App\Models\Setting::get('producto_id_chancho', 0);
                $chanchoInsumo = $chanchoId > 0
                    ? \DB::table('productos')->where('id',$chanchoId)->first()
                    : \DB::table('productos')->where('categoria','insumo')->where('nombre','like','%chancho%')->first();
            @endphp
            @if($chanchoInsumo)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
                    <div>
                        <strong>{{ $chanchoInsumo->nombre }}</strong><br>
                        <small class="text-gray-600">Stock actual: {{ number_format($chanchoInsumo->stock ?? 0, 2) }} kg</small>
                    </div>
                    <div class="lg:col-span-2">
                        <form method="post" action="{{ route('admin.stock.entrada') }}" class="grid grid-cols-1 md:grid-cols-4 gap-3 items-end" id="formEntradaChancho">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $chanchoInsumo->id }}">
                            <div>
                                <div class="flex">
                                    <input type="number" step="1" min="1" name="costillas" class="w-full px-3 py-2 border border-gray-300 rounded-l-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="costillasChancho" placeholder="Costillas" />
                                    <span class="bg-gray-100 border border-l-0 border-gray-300 px-3 py-2 rounded-r-lg text-sm">costillas</span>
                                </div>
                                <small class="text-gray-500">o ingresa kilos abajo</small>
                            </div>
                            <div>
                                <input type="number" step="0.01" min="0" name="precio_kilo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="precioKiloChancho" placeholder="Bs/kg" value="{{ \App\Models\Setting::get('precio_kilo_chancho', 46) }}" required>
                            </div>
                            <div>
                                <input type="number" step="0.01" min="0.01" name="kilos" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" id="kilosChancho" placeholder="Kilos (opcional)">
                            </div>
                            <div>
                                <button class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">Comprar</button>
                            </div>
                        </form>
                        
                        <div class="mt-3">
                            <div class="flex items-center gap-2 text-sm">
                                <span>Atajos:</span>
                                @foreach([3,4,5,8,10] as $btnKg)
                                    <button type="button" class="bg-red-100 hover:bg-red-200 text-red-700 px-2 py-1 rounded text-xs quick-kg" data-kg="{{ $btnKg }}">{{ $btnKg }} kg</button>
                                @endforeach
                            </div>
                            <small class="text-gray-600">Total estimado: <strong id="totalEstimadoChancho">Bs. 0</strong></small>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Configura el producto base de chancho en "Configuración de stock".
                </div>
            @endif
        </div>
    </div>

    <!-- Entradas de Pollo -->
    <div class="border border-yellow-200 rounded-lg mb-4">
        <div class="bg-yellow-500 text-white px-4 py-2 rounded-t-lg">
            <h6 class="font-semibold">🐔 Pollo Entero</h6>
        </div>
        <div class="p-4">
            @php
                $polloId = (int) \App\Models\Setting::get('producto_id_pollo', 0);
                $polloInsumo = $polloId > 0
                    ? \DB::table('productos')->where('id',$polloId)->first()
                    : \DB::table('productos')->where('categoria','insumo')->where('nombre','like','%pollo%')->first();
            @endphp
            @if($polloInsumo)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
                    <div>
                        <strong>{{ $polloInsumo->nombre }}</strong><br>
                        <small class="text-gray-600">Stock actual: {{ number_format($polloInsumo->stock ?? 0, 0) }} pollos</small>
                    </div>
                    <div class="lg:col-span-2">
                        <form method="post" action="{{ route('admin.stock.entrada') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $polloInsumo->id }}">
                            <input type="number" step="1" min="1" name="unidades" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Pollos comprados" required>
                            <input type="number" step="0.01" min="0" name="precio_kilo" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" placeholder="Bs/pollo" value="{{ \App\Models\Setting::get('precio_kilo_pollo', 55) }}" required>
                            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg">Comprar</button>
                        </form>
                    </div>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded">
                    Configura el producto base de pollo en "Configuración de stock".
                </div>
            @endif
        </div>
    </div>

    <!-- Entradas de Refrescos -->
    <div class="border border-blue-200 rounded-lg">
        <div class="bg-blue-500 text-white px-4 py-2 rounded-t-lg">
            <h6 class="font-semibold">🥤 Refrescos</h6>
        </div>
        <div class="p-4 space-y-3">
            @foreach($productos->where('categoria','refresco') as $refresco)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center">
                    <div>
                        <strong>{{ $refresco->nombre }}</strong><br>
                        <small class="text-gray-600">Stock: {{ number_format($refresco->stock ?? 0, 0) }} unidades</small>
                    </div>
                    <div class="lg:col-span-2">
                        <form method="post" action="{{ route('admin.stock.entrada') }}" class="grid grid-cols-2 gap-3">
                            @csrf
                            <input type="hidden" name="producto_id" value="{{ $refresco->id }}">
                            <input type="number" step="1" min="1" name="unidades" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Unidades compradas" required>
                            <input type="hidden" name="precio_kilo" value="{{ $refresco->precio }}">
                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Comprar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kilosSelect = document.getElementById('kilosChancho');
    const precioInput = document.getElementById('precioKiloChancho');
    const totalLabel = document.getElementById('totalEstimadoChancho');
    const costillasInput = document.getElementById('costillasChancho');
    const pesoProm = parseFloat({{ (float) \App\Models\Setting::get('peso_costilla_prom_kg', 1.2) }});
    
    function updateTotal() {
        let k = parseFloat(kilosSelect?.value || 0);
        const c = parseFloat(costillasInput?.value || 0);
        if (!isNaN(c) && c > 0) {
            k = c * (isFinite(pesoProm) ? pesoProm : 1.2);
        }
        const p = parseFloat(precioInput?.value || 0);
        const total = (isFinite(k) && isFinite(p)) ? (k * p) : 0;
        if (totalLabel) totalLabel.textContent = 'Bs. ' + (Math.round(total * 100) / 100).toFixed(2);
    }
    
    document.querySelectorAll('.quick-kg').forEach(function(btn){
        btn.addEventListener('click', function(){
            const kg = parseFloat(this.getAttribute('data-kg'));
            if (!isNaN(kg) && kilosSelect){ kilosSelect.value = kg; kilosSelect.dispatchEvent(new Event('change')); }
        });
    });
    
    if (kilosSelect) kilosSelect.addEventListener('change', updateTotal);
    if (costillasInput) costillasInput.addEventListener('input', updateTotal);
    if (precioInput) precioInput.addEventListener('input', updateTotal);
    updateTotal();
});
</script>
@endsection
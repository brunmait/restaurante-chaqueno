@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-900">Productos</h3>
    @if (session('status'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('status') }}
        </div>
    @endif
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="space-y-6">
        <!-- Registrar producto: Chancho -->
        <div class="bg-white rounded-lg shadow">
            <div class="bg-red-500 text-white px-4 py-3 rounded-t-lg">
                <strong>Registrar producto de Chancho</strong>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('admin.productos.store') }}">
                    @csrf
                    <input type="hidden" name="categoria" value="chancho">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" name="nombre" placeholder="Ej. Chancho a la Cruz - 2 Costillas" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio (Bs.)</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" name="precio" type="number" step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500" name="descripcion" rows="2" placeholder="Opcional"></textarea>
                        </div>
                        <div class="bg-red-50 p-3 rounded text-sm">
                            Unidad de compra: <strong>kilos</strong> (se gestiona en Entradas de Stock)
                        </div>
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-800 px-3 py-2 rounded">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrar producto: Pollo -->
        <div class="bg-white rounded-lg shadow">
            <div class="bg-yellow-500 text-white px-4 py-3 rounded-t-lg">
                <strong>Registrar producto de Pollo</strong>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('admin.productos.store') }}">
                    @csrf
                    <input type="hidden" name="categoria" value="pollo">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" name="nombre" placeholder="Ej. Pollo a la Leña - Media Porción" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio (Bs.)</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" name="precio" type="number" step="0.01" min="0" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500" name="descripcion" rows="2" placeholder="Opcional"></textarea>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded text-sm">
                            Unidad de compra: <strong>unidades</strong> (pollos enteros)
                        </div>
                        <button class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-lg">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Registrar producto: Refrescos -->
        <div class="bg-white rounded-lg shadow">
            <div class="bg-blue-500 text-white px-4 py-3 rounded-t-lg">
                <strong>Registrar Bebidas y Refrescos</strong>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('admin.productos.store') }}">
                    @csrf
                    <input type="hidden" name="categoria" value="refresco">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Bebida</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="tipo_bebida" onchange="updateNombre()">
                                    <option value="">Seleccionar...</option>
                                    <option value="Coca Cola">Coca Cola</option>
                                    <option value="Pepsi">Pepsi</option>
                                    <option value="Sprite">Sprite</option>
                                    <option value="Fanta">Fanta</option>
                                    <option value="Agua">Agua</option>
                                    <option value="Jugo">Jugo Natural</option>
                                    <option value="Cerveza">Cerveza</option>
                                    <option value="Otro">Otro</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tamaño</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="tamano" onchange="updateNombre()">
                                    <option value="">Seleccionar...</option>
                                    <option value="350ml">350ml (Lata)</option>
                                    <option value="500ml">500ml (Botella)</option>
                                    <option value="600ml">600ml</option>
                                    <option value="1L">1 Litro</option>
                                    <option value="1.5L">1.5 Litros</option>
                                    <option value="2L">2 Litros</option>
                                    <option value="2.5L">2.5 Litros</option>
                                    <option value="3L">3 Litros</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Producto</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="nombre" id="nombre_producto" placeholder="Ej. Coca Cola 2L" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Precio de Venta (Bs.)</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="precio" type="number" step="0.01" min="0" placeholder="Precio al cliente" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock Inicial (unidades)</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="stock_inicial" type="number" min="0" value="0" placeholder="Cantidad inicial en inventario">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" name="descripcion" rows="2" placeholder="Información adicional (opcional)"></textarea>
                        </div>
                        <div class="bg-blue-50 p-3 rounded text-sm">
                            <strong>💡 Tip:</strong> Las bebidas se manejan por unidades. El stock inicial se puede ajustar después en "Gestionar Stock".
                        </div>
                        <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg font-medium">Agregar Bebida</button>
                    </div>
                </form>
            </div>
        </div>
        
        <script>
        function updateNombre() {
            const tipo = document.querySelector('[name="tipo_bebida"]').value;
            const tamano = document.querySelector('[name="tamano"]').value;
            const nombreInput = document.getElementById('nombre_producto');
            
            if (tipo && tamano) {
                nombreInput.value = tipo + ' ' + tamano;
            }
        }
        </script>
    </div>

    <div class="space-y-6">
        <!-- Configuración de stock -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h5 class="text-lg font-semibold">Configuración de stock</h5>
            </div>
            <div class="p-4">
                <form method="post" action="{{ route('admin.stock.config') }}">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Producto de stock (Chancho)</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="producto_id_chancho">
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}" {{ (int)\App\Models\Setting::get('producto_id_chancho', 0) === (int)$p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Gramos por costilla (Chancho)</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="gramos_costilla" value="{{ \App\Models\Setting::get('gramos_costilla', 375) }}" required>
                            <small class="text-gray-500">Regla: 3 kg de chancho = 8 platos (375 g por plato)</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Peso promedio por costilla (kg)</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="peso_costilla_prom_kg" value="{{ \App\Models\Setting::get('peso_costilla_prom_kg', 1.2) }}" required>
                            <small class="text-gray-500">Se usará para convertir costillas a kilos al registrar compras</small>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo (kg) Chancho</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="stock_min_kilos" value="{{ \App\Models\Setting::get('stock_min_kilos', 5) }}" required>
                            <small class="text-gray-500">Se compra por kilos. Conversión: 3 kg = 8 platos</small>
                        </div>
                        <hr>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Producto de stock (Pollo)</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="producto_id_pollo">
                                @foreach($productos as $p)
                                    <option value="{{ $p->id }}" {{ (int)\App\Models\Setting::get('producto_id_pollo', 0) === (int)$p->id ? 'selected' : '' }}>{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Porciones por pollo</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="porciones_por_pollo" value="{{ \App\Models\Setting::get('porciones_por_pollo', 5) }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stock mínimo (unidades) Pollo</label>
                            <input type="number" step="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-500 focus:border-gray-500" name="stock_min_pollos" value="{{ \App\Models\Setting::get('stock_min_pollos', 3) }}">
                            <small class="text-gray-500">Pollo se compra por unidades</small>
                        </div>
                        <button class="w-full bg-gray-600 hover:bg-gray-700 text-white py-2 px-4 rounded-lg">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Información -->
        <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
            Para registrar compras, usa el módulo <a href="{{ route('admin.stock') }}" class="underline font-medium">Stock</a>.
        </div>
    </div>
</div>
@endsection
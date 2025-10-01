@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50 to-pink-100 p-6" x-data="productosAdmin()">
    <!-- Header Elegante -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 mb-8 border border-slate-200 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-200/30 to-pink-200/30 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-tr from-blue-200/30 to-indigo-200/30 rounded-full -ml-12 -mb-12"></div>
        <div class="relative flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500 rounded-3xl flex items-center justify-center shadow-2xl">
                        <i class="fas fa-boxes text-white text-3xl"></i>
                    </div>
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-emerald-400 rounded-full border-3 border-white animate-pulse"></div>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-slate-800 via-purple-700 to-pink-600 bg-clip-text text-transparent">
                        Gestión de Productos
                    </h1>
                    <p class="text-slate-600 text-xl mt-2">Administra tu inventario por categorías</p>
                    <div class="flex items-center mt-3 space-x-4">
                        <div class="flex items-center text-sm text-slate-500">
                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></div>
                            Sistema activo
                        </div>
                        <div class="text-sm text-slate-500">
                            <i class="fas fa-clock mr-1"></i>
                            Actualizado hoy
                        </div>
                    </div>
                </div>
            </div>
            <button class="bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 text-white px-10 py-5 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-3xl transition-all duration-300 transform hover:scale-105 hover:-translate-y-1" 
                    data-bs-toggle="modal" data-bs-target="#modalNuevoProducto">
                <i class="fas fa-plus mr-3 text-xl"></i>Nuevo Producto
            </button>
        </div>
    </div>

    <!-- Estadísticas Elegantes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-10">
        @foreach($categorias as $categoria)
        <div class="group relative">
            <div class="absolute inset-0 bg-gradient-to-r from-{{ $categoria->color === '#dc2626' ? 'red' : ($categoria->color === '#f59e0b' ? 'amber' : 'blue') }}-200 to-{{ $categoria->color === '#dc2626' ? 'pink' : ($categoria->color === '#f59e0b' ? 'orange' : 'indigo') }}-200 rounded-3xl blur-xl opacity-30 group-hover:opacity-50 transition-all duration-300"></div>
            <div class="relative bg-white rounded-3xl p-8 shadow-xl border border-slate-200 hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105 group-hover:-translate-y-2">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-16 h-16 bg-gradient-to-br {{ $categoria->color === '#dc2626' ? 'from-red-500 to-pink-600' : ($categoria->color === '#f59e0b' ? 'from-amber-500 to-orange-600' : 'from-blue-500 to-indigo-600') }} rounded-2xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all">
                        <i class="{{ $categoria->icono }} text-white text-2xl"></i>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-slate-800">{{ $categoria->productos->count() }}</div>
                        <div class="text-sm text-slate-500 font-medium">productos</div>
                    </div>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $categoria->nombre }}</h3>
                    <p class="text-slate-600 text-sm mb-4">{{ $categoria->descripcion }}</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 {{ $categoria->productos->sum('stock') > 50 ? 'bg-green-400' : ($categoria->productos->sum('stock') > 20 ? 'bg-yellow-400' : 'bg-red-400') }} rounded-full"></div>
                            <span class="text-sm font-semibold text-slate-700">Stock: {{ $categoria->productos->sum('stock') }}</span>
                        </div>
                        <span class="px-3 py-1 bg-gradient-to-r {{ $categoria->color === '#dc2626' ? 'from-red-100 to-pink-100 text-red-700' : ($categoria->color === '#f59e0b' ? 'from-amber-100 to-orange-100 text-amber-700' : 'from-blue-100 to-indigo-100 text-blue-700') }} rounded-full text-xs font-bold">
                            {{ $categoria->productos->where('disponible', true)->count() }} activos
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Categorías con Productos Elegantes -->
    @foreach($categorias as $categoria)
    <div class="bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden mb-10 hover:shadow-3xl transition-all duration-300">
        <div class="relative bg-gradient-to-r {{ $categoria->color === '#dc2626' ? 'from-red-500 via-pink-500 to-rose-500' : ($categoria->color === '#f59e0b' ? 'from-amber-500 via-orange-500 to-yellow-500' : 'from-blue-500 via-indigo-500 to-purple-500') }} px-8 py-6">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="relative flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-14 h-14 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center">
                        <i class="{{ $categoria->icono }} text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            {{ $categoria->nombre }}
                            <span class="ml-4 px-3 py-1 bg-white/20 backdrop-blur-sm text-white rounded-full text-sm font-bold">
                                {{ $categoria->productos->count() }}
                            </span>
                        </h2>
                        <p class="text-white/80 mt-1">{{ $categoria->descripcion }}</p>
                    </div>
                </div>
                <button class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 transform hover:scale-105" @click="nuevoProducto({{ $categoria->id }})">
                    <i class="fas fa-plus mr-2"></i>Agregar
                </button>
            </div>
        </div>
        <div class="p-8">
            @if($categoria->productos->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($categoria->productos as $producto)
                    <div class="group bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-6 border border-slate-200 hover:shadow-xl hover:border-slate-300 transition-all duration-300 transform hover:scale-102">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-gradient-to-br {{ $categoria->color === '#dc2626' ? 'from-red-100 to-pink-100' : ($categoria->color === '#f59e0b' ? 'from-amber-100 to-orange-100' : 'from-blue-100 to-indigo-100') }} rounded-xl flex items-center justify-center">
                                    <i class="{{ $categoria->icono }} {{ $categoria->color === '#dc2626' ? 'text-red-600' : ($categoria->color === '#f59e0b' ? 'text-amber-600' : 'text-blue-600') }} text-lg"></i>
                                </div>
                                <div class="flex-1">
                                    <h3 class="font-bold text-lg text-slate-800 group-hover:text-slate-900 transition-colors">{{ $producto->nombre }}</h3>
                                    @if($producto->descripcion)
                                        <p class="text-slate-600 text-sm mt-1">{{ Str::limit($producto->descripcion, 60) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all" @click="editarProducto({{ $producto }})" title="Editar">
                                    <i class="fas fa-edit text-xs"></i>
                                </button>
                                <form method="POST" action="{{ route('admin.productos.destroy', $producto) }}" class="inline" onsubmit="return confirm('¿Eliminar este producto?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all" title="Eliminar">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-emerald-600">Bs. {{ number_format($producto->precio, 2) }}</div>
                                    <div class="text-xs text-slate-500 font-medium">Precio</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold {{ $producto->stock > 10 ? 'text-green-600' : ($producto->stock > 0 ? 'text-yellow-600' : 'text-red-600') }}">{{ $producto->stock }}</div>
                                    <div class="text-xs text-slate-500 font-medium">Stock</div>
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('admin.productos.toggle', $producto) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="px-4 py-2 rounded-xl font-semibold text-sm transition-all transform hover:scale-105 {{ $producto->disponible ? 'bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white shadow-lg' : 'bg-gradient-to-r from-slate-400 to-slate-500 hover:from-slate-500 hover:to-slate-600 text-white' }}">
                                        <i class="fas {{ $producto->disponible ? 'fa-check' : 'fa-times' }} mr-2"></i>
                                        {{ $producto->disponible ? 'Disponible' : 'No disponible' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-gradient-to-br {{ $categoria->color === '#dc2626' ? 'from-red-100 to-pink-100' : ($categoria->color === '#f59e0b' ? 'from-amber-100 to-orange-100' : 'from-blue-100 to-indigo-100') }} rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="{{ $categoria->icono }} {{ $categoria->color === '#dc2626' ? 'text-red-500' : ($categoria->color === '#f59e0b' ? 'text-amber-500' : 'text-blue-500') }} text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-600 mb-2">No hay productos en esta categoría</h3>
                    <p class="text-slate-500 mb-6">Agrega tu primer producto para comenzar</p>
                    <button class="bg-gradient-to-r {{ $categoria->color === '#dc2626' ? 'from-red-500 to-pink-600 hover:from-red-600 hover:to-pink-700' : ($categoria->color === '#f59e0b' ? 'from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700' : 'from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700') }} text-white px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all transform hover:scale-105" @click="nuevoProducto({{ $categoria->id }})">
                        <i class="fas fa-plus mr-3"></i>Agregar primer producto
                    </button>
                </div>
            @endif
        </div>
    </div>
    @endforeach

    <!-- Modal Nuevo/Editar Producto Elegante -->
    <div class="modal fade" id="modalNuevoProducto" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-2xl rounded-3xl overflow-hidden">
                <form method="POST" :action="editando ? `/admin/productos/${productoEditando.id}` : '{{ route('admin.productos.store') }}'">
                    @csrf
                    <div x-show="editando">@method('PUT')</div>
                    
                    <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 px-8 py-6">
                        <h2 class="text-3xl font-bold text-white flex items-center" x-text="editando ? 'Editar Producto' : 'Nuevo Producto'">
                        </h2>
                        <p class="text-purple-100 mt-2" x-text="editando ? 'Modifica la información del producto' : 'Agrega un nuevo producto al inventario'"></p>
                    </div>
                    <div class="p-8 bg-gradient-to-br from-slate-50 to-purple-50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Categoría *</label>
                                <select name="categoria_id" class="w-full px-4 py-4 bg-white border-2 border-slate-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all text-lg" x-model="form.categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    @foreach($categorias as $cat)
                                        <option value="{{ $cat->id }}">{{ $cat->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Nombre del Producto *</label>
                                <input type="text" name="nombre" class="w-full px-4 py-4 bg-white border-2 border-slate-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all text-lg" x-model="form.nombre" placeholder="Ej: Pollo a la leña" required>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-slate-700 mb-2">Descripción</label>
                            <textarea name="descripcion" class="w-full px-4 py-4 bg-white border-2 border-slate-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all text-lg" rows="3" x-model="form.descripcion" placeholder="Describe las características del producto..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Precio (Bs.) *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <span class="text-slate-500 text-lg font-bold">Bs.</span>
                                    </div>
                                    <input type="number" name="precio" class="w-full pl-12 pr-4 py-4 bg-white border-2 border-slate-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all text-lg font-semibold" step="0.01" x-model="form.precio" placeholder="0.00" required>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-700">Stock Inicial *</label>
                                <input type="number" name="stock" class="w-full px-4 py-4 bg-white border-2 border-slate-300 rounded-2xl focus:ring-4 focus:ring-purple-200 focus:border-purple-500 transition-all text-lg font-semibold" x-model="form.stock" placeholder="0" required>
                            </div>
                        </div>
                        
                        <!-- Preview del Producto -->
                        <div class="bg-white rounded-2xl p-6 border-2 border-purple-200" x-show="form.nombre || form.precio">
                            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center">
                                <i class="fas fa-eye mr-2 text-purple-500"></i>
                                Vista Previa
                            </h3>
                            <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-pink-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-box text-purple-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-800" x-text="form.nombre || 'Nombre del producto'"></h4>
                                        <p class="text-sm text-slate-600" x-text="form.descripcion || 'Descripción del producto'"></p>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between">
                                    <div class="text-2xl font-bold text-emerald-600">Bs. <span x-text="form.precio || '0.00'"></span></div>
                                    <div class="text-lg font-semibold text-slate-700">Stock: <span x-text="form.stock || '0'"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white px-8 py-6 border-t-2 border-slate-200 flex justify-end space-x-4">
                        <button type="button" class="px-8 py-4 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-2xl transition-all text-lg" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="px-10 py-4 bg-gradient-to-r from-purple-500 via-pink-500 to-rose-500 hover:from-purple-600 hover:via-pink-600 hover:to-rose-600 text-white font-bold rounded-2xl shadow-xl hover:shadow-2xl transition-all transform hover:scale-105 text-lg" x-text="editando ? 'Actualizar Producto' : 'Crear Producto'">
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function productosAdmin() {
    return {
        editando: false,
        productoEditando: {},
        form: {
            categoria_id: '',
            nombre: '',
            descripcion: '',
            precio: '',
            stock: ''
        },
        
        nuevoProducto(categoriaId = null) {
            this.editando = false;
            this.form = {
                categoria_id: categoriaId || '',
                nombre: '',
                descripcion: '',
                precio: '',
                stock: ''
            };
            new bootstrap.Modal(document.getElementById('modalNuevoProducto')).show();
        },
        
        editarProducto(producto) {
            this.editando = true;
            this.productoEditando = producto;
            this.form = {
                categoria_id: producto.categoria_id,
                nombre: producto.nombre,
                descripcion: producto.descripcion || '',
                precio: producto.precio,
                stock: producto.stock
            };
            new bootstrap.Modal(document.getElementById('modalNuevoProducto')).show();
        }
    }
}
</script>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

@keyframes pulse-glow {
    0%, 100% { box-shadow: 0 0 20px rgba(168, 85, 247, 0.4); }
    50% { box-shadow: 0 0 30px rgba(168, 85, 247, 0.8); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

.animate-pulse-glow {
    animation: pulse-glow 2s ease-in-out infinite;
}

.hover\:scale-102:hover {
    transform: scale(1.02);
}

.group:hover .group-hover\:shadow-xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
}
</style>
@endsection
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 p-6" x-data="proveedoresAdmin()">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-slate-200">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-truck text-white text-2xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-slate-800 to-slate-600 bg-clip-text text-transparent">
                        Gestión de Proveedores
                    </h1>
                    <p class="text-slate-600 text-lg">Administra los proveedores de costillas</p>
                </div>
            </div>
            <button class="bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white px-8 py-4 rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105" 
                    data-bs-toggle="modal" data-bs-target="#modalNuevoProveedor">
                <i class="fas fa-plus mr-3"></i>Nuevo Proveedor
            </button>
        </div>
    </div>

    <!-- Lista de Proveedores -->
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        <div class="bg-gradient-to-r from-slate-800 to-slate-700 px-8 py-6">
            <h2 class="text-2xl font-bold text-white flex items-center">
                <i class="fas fa-list mr-3"></i>
                Lista de Proveedores
            </h2>
        </div>
        
        <div class="p-8">
            @if($proveedores->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($proveedores as $proveedor)
                    <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-6 border border-slate-200 hover:shadow-lg transition-all">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
                                <i class="fas fa-truck text-white"></i>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $proveedor->nombre }}</h3>
                        <div class="space-y-2 text-sm text-slate-600">
                            @if($proveedor->telefono)
                                <p class="flex items-center">
                                    <i class="fas fa-phone mr-2 text-blue-500"></i>
                                    {{ $proveedor->telefono }}
                                </p>
                            @endif
                            @if($proveedor->direccion)
                                <p class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>
                                    {{ $proveedor->direccion }}
                                </p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-truck text-4xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-600 mb-2">No hay proveedores registrados</h3>
                    <p class="text-slate-500">Registra tu primer proveedor para comenzar</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Nuevo Proveedor -->
    <div class="modal fade" id="modalNuevoProveedor" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-2xl rounded-2xl overflow-hidden">
                <form method="POST" action="{{ route('admin.proveedores.store') }}">
                    @csrf
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-plus-circle mr-3"></i>
                            Registrar Nuevo Proveedor
                        </h2>
                    </div>
                    <div class="p-8 bg-slate-50">
                        <div class="grid grid-cols-1 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Nombre del Proveedor *</label>
                                <input type="text" name="nombre" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Teléfono</label>
                                <input type="text" name="telefono" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-semibold text-slate-700">Dirección</label>
                                <textarea name="direccion" class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white px-8 py-6 border-t border-slate-200 flex justify-end space-x-4">
                        <button type="button" class="px-6 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold rounded-xl transition-all" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                            <i class="fas fa-save mr-2"></i>
                            Registrar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function proveedoresAdmin() {
    return {
        // Funciones para gestión de proveedores
    }
}
</script>
@endsection
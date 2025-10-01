@extends('layouts.admin')

@section('content')
@php
    $admin = auth()->user();
    $resto = 'Restaurante El Chaqueño';
@endphp

<!-- Hero header -->
<div class="bg-gradient-to-br from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl overflow-hidden mb-8 shadow-2xl" style="background-image: url('{{ asset('images/fondo.jpg') }}'); background-size: cover; background-position: center;">
    <div class="bg-gradient-to-r from-slate-900/70 to-slate-800/50 p-8">
        <div class="text-white">
            <h3 class="text-3xl font-bold mb-3 flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-utensils text-2xl"></i>
                </div>
                {{ $resto }}
            </h3>
            <div class="text-emerald-100 text-lg font-medium">Panel de administración</div>
            <div class="mt-4 flex items-center space-x-2">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <span class="text-green-200 text-sm">Sistema operativo</span>
            </div>
        </div>
    </div>
</div>

@if (session('status'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
        {{ session('status') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Perfil admin -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow">
        <div class="flex items-center space-x-4">
            <div class="relative">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg">
                    {{ strtoupper(substr($admin->nombre ?? $admin->email, 0, 1)) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 border-2 border-white rounded-full"></div>
            </div>
            <div>
                <div class="text-slate-500 text-sm font-medium">👨‍💼 Administrador</div>
                <div class="font-bold text-lg text-slate-800">{{ $admin->nombre ?? $admin->email }}</div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-emerald-100 to-green-100 text-emerald-800 border border-emerald-200 mt-2">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></div>Activo
                </span>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow">
        <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center">
            ⚡ Acciones Rápidas
        </h3>
        <div class="space-y-3">
            <a href="{{ route('admin.cajeros.create') }}" class="flex items-center p-3 bg-gradient-to-r from-blue-50 to-indigo-50 hover:from-blue-100 hover:to-indigo-100 rounded-xl transition-all border border-blue-200 hover:border-blue-300">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-user-plus text-white text-sm"></i>
                </div>
                <span class="text-sm font-medium text-slate-700">Registrar Cajero</span>
            </a>
            <a href="{{ route('admin.costillas.index') }}" class="flex items-center p-3 bg-gradient-to-r from-red-50 to-orange-50 hover:from-red-100 hover:to-orange-100 rounded-xl transition-all border border-red-200 hover:border-red-300">
                <div class="w-8 h-8 bg-gradient-to-r from-red-500 to-orange-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-bacon text-white text-sm"></i>
                </div>
                <span class="text-sm font-medium text-slate-700">Gestionar Costillas</span>
            </a>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 hover:shadow-xl transition-shadow">
        <h3 class="text-lg font-semibold mb-4 text-slate-800 flex items-center">
            📊 Resumen
        </h3>
        <div class="space-y-4">
            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 p-4 rounded-xl border border-emerald-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-users text-white text-sm"></i>
                        </div>
                        <span class="text-slate-600 font-medium">Cajeros</span>
                    </div>
                    <span class="font-bold text-2xl text-emerald-700">{{ count($cajeros) }}</span>
                </div>
            </div>
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-4 rounded-xl border border-blue-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-shopping-cart text-white text-sm"></i>
                        </div>
                        <span class="text-slate-600 font-medium">Ventas Hoy</span>
                    </div>
                    <span class="font-bold text-2xl text-blue-700">{{ $ventasHoy }}</span>
                </div>
            </div>
            <div class="bg-gradient-to-r from-orange-50 to-red-50 p-4 rounded-xl border border-orange-200">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-r from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-bacon text-white text-sm"></i>
                        </div>
                        <span class="text-slate-600 font-medium">Stock</span>
                    </div>
                    <span class="font-bold text-2xl {{ $stockActual <= 10 ? 'text-red-700' : 'text-orange-700' }}">{{ $stockActual }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reportes de Ventas -->
@if(count($reportesNoLeidos) > 0)
<div class="mt-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <h3 class="text-lg font-semibold flex items-center text-slate-800">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-chart-line text-white text-sm"></i>
                </div>
                Reportes de Ventas
                <span class="ml-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">{{ count($reportesNoLeidos) }}</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="space-y-3">
                @foreach($reportesNoLeidos as $reporte)
                <div class="bg-gradient-to-r {{ $reporte->tipo == 'ventas_fin_dia' ? 'from-green-50 to-emerald-50 border-green-200' : 'from-blue-50 to-indigo-50 border-blue-200' }} p-4 rounded-xl border hover:shadow-md transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <div class="w-6 h-6 {{ $reporte->tipo == 'ventas_fin_dia' ? 'bg-green-500' : 'bg-blue-500' }} rounded-full flex items-center justify-center mr-2">
                                    <i class="fas {{ $reporte->tipo == 'ventas_fin_dia' ? 'fa-check-circle' : 'fa-chart-bar' }} text-white text-xs"></i>
                                </div>
                                <span class="font-semibold text-sm {{ $reporte->tipo == 'ventas_fin_dia' ? 'text-green-800' : 'text-blue-800' }}">
                                    {{ str_replace(['ventas_', '_'], ['', ' '], $reporte->tipo) }}
                                </span>
                                <span class="ml-2 text-xs text-slate-500">{{ $reporte->fecha_reporte ? $reporte->fecha_reporte->format('d/m/Y') : $reporte->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-sm text-slate-700 mb-2">{{ $reporte->datos['observaciones'] ?? $reporte->titulo }}</p>
                            <div class="text-xs text-slate-600">
                                Pedidos: {{ $reporte->total_pedidos }} | Costillas: {{ $reporte->datos['total_costillas'] ?? 0 }} | Ingresos: Bs.{{ number_format($reporte->total_ventas, 2) }}
                            </div>
                        </div>
                        <button onclick="marcarLeido({{ $reporte->id }})" class="ml-4 text-slate-400 hover:text-slate-600 transition-colors">
                            <i class="fas fa-check text-sm"></i>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-4 text-center">
                <a href="{{ route('admin.reportes.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Ver todos los reportes →
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Cajeros registrados -->
@if(count($cajeros) > 0)
<div class="mt-8">
    <div class="bg-white rounded-2xl shadow-lg border border-slate-200">
        <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-slate-50 to-slate-100 rounded-t-2xl">
            <h3 class="text-lg font-semibold flex items-center text-slate-800">
                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-users text-white text-sm"></i>
                </div>
                Cajeros registrados
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($cajeros as $cajero)
                <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-xl p-4 border border-slate-200 hover:shadow-md transition-all hover:border-slate-300">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold shadow-md">
                            {{ strtoupper(substr($cajero->nombre, 0, 1)) }}
                        </div>
                        <div class="flex-1">
                            <div class="font-medium text-slate-800">{{ $cajero->nombre }}</div>
                            <div class="text-sm text-slate-600">{{ $cajero->email }}</div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200 mt-2">
                                {{ $cajero->role->nombre_rol ?? 'cajero' }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-slate-100 border-t border-slate-200 rounded-b-2xl">
            <div class="text-sm text-slate-600 flex items-center">
                <i class="fas fa-info-circle text-slate-400 mr-2"></i>
                Gestiona usuarios desde el módulo <strong class="text-slate-800">Usuarios</strong> del menú lateral.
            </div>
        </div>
    </div>
</div>
@endif
@endsection

<script>
function marcarLeido(reporteId) {
    fetch(`/admin/reportes/${reporteId}/marcar-leido`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
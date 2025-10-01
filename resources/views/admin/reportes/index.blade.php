@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 mb-8">
    <div class="px-6 py-5 border-b border-slate-200 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-t-2xl">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold flex items-center text-slate-800">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
                Reportes del Sistema
            </h1>
            <div class="flex space-x-3">
                <a href="{{ route('admin.reportes.generar-mensual') }}" class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-4 py-2 rounded-lg hover:from-blue-600 hover:to-indigo-700 transition-all text-sm font-medium">
                    <i class="fas fa-calendar-alt mr-2"></i>Generar Reporte Mensual
                </a>
            </div>
        </div>
    </div>
</div>

@if($reportes->count() > 0)
<div class="bg-white rounded-2xl shadow-lg border border-slate-200">
    <div class="p-6">
        <div class="space-y-4">
            @foreach($reportes as $reporte)
            <div class="bg-gradient-to-r 
                {{ $reporte->tipo == 'ventas_fin_dia' ? 'from-green-50 to-emerald-50 border-green-200' : 
                   ($reporte->tipo == 'ventas_mensual' ? 'from-purple-50 to-indigo-50 border-purple-200' : 'from-blue-50 to-indigo-50 border-blue-200') }} 
                p-6 rounded-xl border hover:shadow-md transition-all">
                
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <div class="w-8 h-8 
                                {{ $reporte->tipo == 'ventas_fin_dia' ? 'bg-green-500' : 
                                   ($reporte->tipo == 'ventas_mensual' ? 'bg-purple-500' : 'bg-blue-500') }} 
                                rounded-lg flex items-center justify-center mr-3">
                                <i class="fas 
                                    {{ $reporte->tipo == 'ventas_fin_dia' ? 'fa-check-circle' : 
                                       ($reporte->tipo == 'ventas_mensual' ? 'fa-calendar-alt' : 'fa-chart-bar') }} 
                                    text-white text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg 
                                    {{ $reporte->tipo == 'ventas_fin_dia' ? 'text-green-800' : 
                                       ($reporte->tipo == 'ventas_mensual' ? 'text-purple-800' : 'text-blue-800') }}">
                                    {{ str_replace(['ventas_', '_'], ['', ' '], $reporte->tipo) }}
                                </h3>
                                <p class="text-sm text-slate-600">{{ $reporte->fecha_reporte ? $reporte->fecha_reporte->format('d/m/Y') : $reporte->created_at->format('d/m/Y') }} - {{ $reporte->created_at->format('H:i') }}</p>
                            </div>
                            @if(!isset($reporte->datos['leido']) || !$reporte->datos['leido'])
                            <span class="ml-3 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">Nuevo</span>
                            @endif
                        </div>
                        
                        <p class="text-slate-700 mb-4">{{ $reporte->datos['observaciones'] ?? $reporte->titulo }}</p>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1">Total Pedidos</div>
                                <div class="font-bold text-lg text-slate-800">{{ $reporte->total_pedidos }}</div>
                            </div>
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1">Costillas Vendidas</div>
                                <div class="font-bold text-lg text-slate-800">{{ $reporte->datos['total_costillas'] ?? 0 }}</div>
                            </div>
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1">Ingresos Totales</div>
                                <div class="font-bold text-lg text-green-600">Bs.{{ number_format($reporte->total_ventas, 2) }}</div>
                            </div>
                            <div class="bg-white/50 p-3 rounded-lg">
                                <div class="text-xs text-slate-500 mb-1">Fecha</div>
                                <div class="font-bold text-lg text-slate-800">
                                    {{ $reporte->fecha_reporte ? $reporte->fecha_reporte->format('d/m/Y') : $reporte->created_at->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if(!isset($reporte->datos['leido']) || !$reporte->datos['leido'])
                    <button onclick="marcarLeido({{ $reporte->id }})" class="ml-4 bg-slate-100 hover:bg-slate-200 text-slate-600 px-3 py-2 rounded-lg transition-colors text-sm">
                        <i class="fas fa-check mr-1"></i>Marcar como leído
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="mt-6">
            {{ $reportes->links() }}
        </div>
    </div>
</div>
@else
<div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center">
    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <i class="fas fa-chart-bar text-slate-400 text-2xl"></i>
    </div>
    <h3 class="text-lg font-semibold text-slate-800 mb-2">No hay reportes disponibles</h3>
    <p class="text-slate-600">Los reportes se generan automáticamente cuando ocurren eventos importantes.</p>
</div>
@endif

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
@endsection
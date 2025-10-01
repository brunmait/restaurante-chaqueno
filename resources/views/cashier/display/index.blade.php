@extends('layouts.cajero')

@section('title', 'Pantalla de Pedidos')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <i class="fas fa-tv mr-3 text-purple-600"></i>
            Pantalla de Órdenes
        </h1>
        <p class="text-gray-600 mt-1">Monitor en tiempo real de ventas directas y pedidos online</p>
    </div>
    <div class="flex items-center space-x-3">
        <form method="post" action="{{ route('cashier.display.clear') }}" class="inline">
            @csrf
            <input type="hidden" name="ids[]" value="">
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
                <i class="fas fa-trash"></i>
                <span>Limpiar Pantalla</span>
            </button>
        </form>
        <a href="{{ route('cashier.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2">
            <i class="fas fa-arrow-left"></i>
            <span>Volver</span>
        </a>
    </div>
</div>

<!-- Display Grid -->
<div id="displayGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"></div>
<script>
    (function(){
        const grid = document.getElementById('displayGrid');
        async function load(){
            const res = await fetch("{{ route('cashier.display.feed') }}", {cache:'no-store'});
            const data = await res.json();
            grid.innerHTML = '';
            if (!data.length){
                grid.innerHTML = '<div class="col-span-full text-center py-12"><div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"><i class="fas fa-tv text-gray-400 text-2xl"></i></div><h3 class="text-lg font-medium text-gray-900 mb-2">No hay órdenes activas</h3><p class="text-gray-500">Las ventas directas y pedidos online aparecerán aquí en tiempo real</p></div>';
                return;
            }
            data.forEach(function(d){
                const col = document.createElement('div');
                col.className = '';
                const hora = new Date(d.creado_en).toLocaleTimeString().slice(0,5);
                const lineas = (String(d.detalle||'').split(',').map(s => s.trim()).filter(Boolean) || []);
                const lista = lineas.map(s => `<div class=\"bg-gray-50 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700\">${s}</div>`).join('');
                
                // Colores según tipo
                const isOnline = d.tipo === 'pedido';
                const borderColor = isOnline ? 'border-blue-200' : 'border-green-200';
                const headerGradient = isOnline ? 'from-blue-600 to-blue-700' : 'from-green-600 to-green-700';
                const totalBg = isOnline ? 'bg-blue-50 border-blue-200' : 'bg-green-50 border-green-200';
                const totalText = isOnline ? 'text-blue-700' : 'text-green-700';
                const tipoIcon = isOnline ? '📱' : '🏠';
                const estadoPago = d.estado_pago === 'pagado' ? '<span class=\"bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-medium ml-2\">Pagado</span>' : (isOnline ? '<span class=\"bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium ml-2\">Pendiente Pago</span>' : '');
                
                col.innerHTML = `
                <div class=\"bg-white rounded-xl shadow-lg border-2 ${borderColor} overflow-hidden hover:shadow-xl transition-shadow duration-300\">
                    <div class=\"bg-gradient-to-r ${headerGradient} px-4 py-3\">
                        <div class=\"flex justify-between items-center mb-2\">
                            <h5 class=\"text-white font-semibold text-lg\">${d.apellido ?? 'Cliente'}</h5>
                            <span class=\"bg-white bg-opacity-20 text-white px-3 py-1 rounded-full text-sm font-medium\">${hora}</span>
                        </div>
                        <div class=\"flex items-center justify-between\">
                            <span class=\"bg-white bg-opacity-20 text-white px-2 py-1 rounded-full text-xs font-medium\">${tipoIcon} ${d.origen ?? (isOnline ? 'Pedido Online' : 'Venta Directa')}</span>
                            ${estadoPago}
                        </div>
                    </div>
                    <div class=\"p-4\">
                        ${(d.referencia||'') ? `<div class=\"text-sm text-gray-500 mb-3 font-medium\">${d.referencia}</div>` : ''}
                        <div class=\"space-y-2 mb-4\">
                            ${lista}
                        </div>
                        ${d.observacion ? `<div class=\"bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4\"><div class=\"text-sm text-amber-800\"><strong>📝 Observación:</strong> ${d.observacion}</div></div>` : ''}
                        <div class=\"${totalBg} border rounded-lg p-3 mb-4\">
                            <div class=\"text-lg font-bold ${totalText}\">Total: Bs. ${Number(d.total).toFixed(0)}</div>
                        </div>
                        <div class=\"flex space-x-2\">
                            <button data-id=\"${d.id}\" data-estado=\"mostrado\" class=\"flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors btn-estado\" ${d.estado==='mostrado'||d.estado==='preparando'?'disabled':''}>${isOnline ? '🍳 Preparando' : '👁️ Mostrado'}</button>
                            <button data-id=\"${d.id}\" data-estado=\"cerrado\" class=\"flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-2 rounded-lg text-sm font-medium transition-colors btn-estado\">${isOnline ? '✅ Completar' : '❌ Cerrar'}</button>
                        </div>
                    </div>
                </div>`;
                grid.appendChild(col);
            });
            bindActions();
        }
        function bindActions(){
            document.querySelectorAll('.btn-estado').forEach(function(b){
                b.onclick = async function(){
                    const id = this.getAttribute('data-id');
                    const estado = this.getAttribute('data-estado');
                    const base = `{{ route('cashier.display.update', ['id' => 0]) }}`;
                    const url = base.replace('/0/estado', `/${id}/estado`);
                    await fetch(url,{
                        method:'POST',
                        headers: {'Content-Type':'application/x-www-form-urlencoded','X-CSRF-TOKEN':'{{ csrf_token() }}'},
                        body: new URLSearchParams({estado})
                    });
                    load();
                };
            });
        }
        load();
        setInterval(load, 10000);
    })();
</script>
@endsection



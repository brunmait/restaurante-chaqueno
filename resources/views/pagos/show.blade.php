@extends('layouts.site')

@section('content')
<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-2xl p-8 text-center">
        <div class="mb-6">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-qrcode text-emerald-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 mb-2">Pagar Pedido</h1>
            <p class="text-slate-600">{{ $payment->concepto }}</p>
        </div>

        <div class="bg-slate-50 rounded-xl p-6 mb-6">
            <div class="text-3xl font-bold text-emerald-600 mb-2">
                Bs. {{ number_format($payment->monto_centavos/100, 0) }}
            </div>
            <div class="text-sm text-slate-500">{{ $payment->currency }}</div>
        </div>

        <!-- QR Code Simple -->
        <div class="bg-white border-2 border-slate-200 rounded-xl p-6 mb-6">
            <div class="w-48 h-48 mx-auto bg-white rounded-lg flex items-center justify-center mb-4 cursor-pointer" onclick="ampliarQR()">
                <img src="{{ asset('images/QR.jpg') }}" alt="QR de Pago" class="w-full h-full object-contain rounded-lg hover:opacity-80 transition-opacity">
            </div>
            <p class="text-xs text-slate-500">Escanea con tu app de pagos</p>
            <p class="text-xs text-blue-600 mt-1">Haz clic para ampliar</p>
        </div>

        <!-- Modal para QR ampliado -->
        <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-75 hidden z-50 flex items-center justify-center" onclick="cerrarQR()">
            <div class="bg-white rounded-xl p-6 max-w-sm mx-4">
                <div class="text-center mb-4">
                    <h3 class="text-lg font-semibold">QR de Pago</h3>
                    <p class="text-sm text-gray-600">Bs. {{ number_format($payment->monto_centavos/100, 0) }}</p>
                </div>
                <img src="{{ asset('images/QR.jpg') }}" alt="QR de Pago" class="w-full h-auto rounded-lg">
                <button onclick="cerrarQR()" class="w-full mt-4 bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded-lg">
                    Cerrar
                </button>
            </div>
        </div>

        @if($payment->status === 'paid')
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
                <div class="flex items-center justify-center text-emerald-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    <span class="font-semibold">¡Pago Confirmado!</span>
                </div>
            </div>
        @else
            <div class="space-y-3">
                <button onclick="simularPago()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 px-6 rounded-xl font-semibold transition-colors">
                    <i class="fas fa-mobile-alt mr-2"></i>
                    Simular Pago (Demo)
                </button>
                
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="text-sm text-blue-800">
                        <strong>Estado:</strong> {{ strtoupper($payment->status) }}
                    </div>
                </div>
            </div>
        @endif

        <div class="mt-6 pt-6 border-t border-slate-200">
            <p class="text-xs text-slate-500">
                ID: {{ $payment->uuid }}
            </p>
        </div>
    </div>
</div>

<script>
async function simularPago() {
    try {
        const response = await fetch('{{ route("pagos.marcar-pagado", $payment->uuid) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        if (response.ok) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function ampliarQR() {
    document.getElementById('qrModal').classList.remove('hidden');
}

function cerrarQR() {
    document.getElementById('qrModal').classList.add('hidden');
}
</script>
@endsection
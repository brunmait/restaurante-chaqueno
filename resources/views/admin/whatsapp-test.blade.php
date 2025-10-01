@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
            <i class="fab fa-whatsapp text-green-500 mr-3"></i>
            Panel de WhatsApp
        </h1>

        <!-- Formulario Enviar Menú -->
        <div class="bg-green-50 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-green-800 mb-4">Enviar Menú Interactivo</h2>
            <form action="{{ route('whatsapp.sendMenu') }}" method="POST" class="flex items-end space-x-3">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Número de WhatsApp</label>
                    <input name="to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="59163217872" value="59163217872">
                </div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Enviar Menú
                </button>
            </form>
        </div>

        <!-- Formulario Enviar QR -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-4">Enviar QR de Pago</h2>
            <form action="{{ route('whatsapp.sendQr') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Número de WhatsApp</label>
                        <input name="to" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="59163217872" value="59163217872">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Enlace de Pago</label>
                        <input name="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="https://tusitio.com/pago/123" value="https://rinconchaqueno.com/pago/123">
                    </div>
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-qrcode mr-2"></i>
                    Enviar QR de Pago
                </button>
            </form>
        </div>

        <!-- Información -->
        <div class="mt-6 bg-gray-50 rounded-lg p-4">
            <h3 class="font-semibold text-gray-800 mb-2">Configuración Actual:</h3>
            <ul class="text-sm text-gray-600 space-y-1">
                <li><strong>Número por defecto:</strong> {{ env('WHATSAPP_DEFAULT_TO', 'No configurado') }}</li>
                <li><strong>API Version:</strong> {{ env('WHATSAPP_API_VER', 'v20.0') }}</li>
                <li><strong>Webhook URL:</strong> {{ url('/api/whatsapp/webhook') }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection
@extends('layouts.public')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">🎉 Promociones Especiales</h1>
        <p class="text-xl text-gray-600">Aprovecha nuestras increíbles ofertas</p>
    </div>

    <!-- Promociones Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        <!-- Promoción 9 Platos -->
        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-8 border-2 border-green-200 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="text-center">
                <div class="w-20 h-20 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-wine-bottle text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-green-800 mb-4">¡Vino Gratis!</h2>
                <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                    <p class="text-lg text-gray-700 mb-2">Compra</p>
                    <p class="text-4xl font-bold text-green-600 mb-2">9 Platos</p>
                    <p class="text-lg text-gray-700">y recibe</p>
                    <p class="text-2xl font-bold text-red-600">1 Vino GRATIS</p>
                </div>
                <div class="flex items-center justify-center space-x-2 text-green-700">
                    <i class="fas fa-clock"></i>
                    <span class="font-semibold">Válido HOY</span>
                </div>
            </div>
        </div>

        <!-- Promoción Cumpleaños -->
        <div class="bg-gradient-to-br from-purple-50 to-pink-100 rounded-2xl p-8 border-2 border-purple-200 shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
            <div class="text-center">
                <div class="w-20 h-20 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-birthday-cake text-white text-3xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-purple-800 mb-4">¡Feliz Cumpleaños!</h2>
                <div class="bg-white rounded-xl p-6 mb-6 shadow-lg">
                    <p class="text-lg text-gray-700 mb-2">Si es tu cumpleaños</p>
                    <p class="text-3xl font-bold text-purple-600 mb-2">🎂</p>
                    <p class="text-lg text-gray-700">recibe</p>
                    <p class="text-2xl font-bold text-pink-600">1 Vino GRATIS</p>
                </div>
                <div class="bg-yellow-100 rounded-lg p-3 text-sm text-yellow-800">
                    <i class="fas fa-id-card mr-2"></i>
                    Presenta tu cédula de identidad
                </div>
            </div>
        </div>
    </div>

    <!-- Términos y Condiciones -->
    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-info-circle text-blue-500 mr-2"></i>
            Términos y Condiciones
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-600">
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Promoción 9 Platos:</h4>
                <ul class="space-y-1">
                    <li>• Válido solo por hoy</li>
                    <li>• Mínimo 9 platos de cualquier precio</li>
                    <li>• 1 vino por mesa</li>
                    <li>• No acumulable con otras promociones</li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Promoción Cumpleaños:</h4>
                <ul class="space-y-1">
                    <li>• Válido el día de tu cumpleaños</li>
                    <li>• Presentar cédula de identidad</li>
                    <li>• Mínimo 1 plato consumido</li>
                    <li>• 1 vino por cumpleañero</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="text-center mt-12">
        <a href="{{ route('public.order') }}" class="inline-flex items-center bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-semibold text-lg transition-colors duration-200 shadow-lg hover:shadow-xl">
            <i class="fas fa-utensils mr-3"></i>
            Hacer Pedido Ahora
        </a>
    </div>
</div>
@endsection



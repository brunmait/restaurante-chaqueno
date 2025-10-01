@extends('layouts.public')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">📞 Contacto</h1>
        <p class="text-xl text-gray-600">Estamos aquí para atenderte</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Información de Contacto -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Información de Contacto</h2>
            
            <div class="space-y-6">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-map-marker-alt text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Ubicación</h3>
                        <p class="text-gray-600">📍 El Alto, Bolivia</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-phone text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Teléfono</h3>
                        <p class="text-gray-600">📞 +591 63217872</p>
                    </div>
                </div>

                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 mb-1">Horarios de Atención</h3>
                        <p class="text-gray-600">🕒 Vie-Sáb-Dom y Feriados: 10:00-15:00</p>
                    </div>
                </div>
            </div>

            <!-- Botón WhatsApp -->
            <div class="mt-8">
                <a href="https://wa.me/59163217872" target="_blank" 
                   class="w-full bg-green-500 hover:bg-green-600 text-white py-4 px-6 rounded-xl font-semibold text-lg transition-colors duration-200 flex items-center justify-center space-x-3 shadow-lg">
                    <i class="fab fa-whatsapp text-2xl"></i>
                    <span>Contáctanos por WhatsApp</span>
                </a>
            </div>
        </div>

        <!-- Formulario de Contacto -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Envíanos un Mensaje</h2>
            
            <form class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="Tu nombre completo">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="Tu número de teléfono">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Correo Electrónico</label>
                    <input type="email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                           placeholder="tu@email.com">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mensaje</label>
                    <textarea rows="4" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500" 
                              placeholder="Escribe tu mensaje aquí..."></textarea>
                </div>
                
                <button type="button" 
                        class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed" 
                        disabled>
                    📧 Enviar Mensaje (Próximamente)
                </button>
                
                <div class="text-center text-sm text-gray-500">
                    Por ahora, contáctanos directamente por WhatsApp
                </div>
            </form>
        </div>
    </div>
</div>
@endsection



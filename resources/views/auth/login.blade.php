@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <!-- Header -->
        <div class="text-center">
            <div class="mx-auto h-16 w-16 bg-chaqueno-500 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-user-shield text-white text-2xl"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mb-2">Panel de Administración</h2>
            <p class="text-gray-600">Accede como administrador o cajero</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <form method="post" action="{{ route('login.post') }}" class="space-y-6">
                @csrf
                
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-chaqueno-500"></i>Correo Electrónico
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ $prefill ?? old('email') }}" 
                           required 
                           autofocus
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-chaqueno-500 focus:border-chaqueno-500 transition-colors duration-200 text-gray-900 placeholder-gray-500"
                           placeholder="admin@elchaqueno.com">
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-chaqueno-500"></i>Contraseña
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-chaqueno-500 focus:border-chaqueno-500 transition-colors duration-200 text-gray-900 placeholder-gray-500"
                           placeholder="••••••••">
                </div>

                <!-- CAPTCHA Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-shield-alt mr-2 text-chaqueno-500"></i>Verificación de Seguridad
                    </label>
                    <div class="flex items-center space-x-3 mb-3">
                        <img src="{{ route('captcha') }}" 
                             alt="captcha" 
                             id="captchaImg" 
                             class="h-12 border-2 border-chaqueno-200 rounded-lg bg-white">
                        <button type="button" 
                                onclick="document.getElementById('captchaImg').src='{{ route('captcha') }}?r='+Date.now();"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors duration-200 text-sm font-medium">
                            <i class="fas fa-sync-alt mr-1"></i>Recargar
                        </button>
                    </div>
                    <input type="text" 
                           name="captcha" 
                           placeholder="Escribe las letras en mayúsculas" 
                           required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-chaqueno-500 focus:border-chaqueno-500 transition-colors duration-200 text-gray-900 placeholder-gray-500">
                    @error('captcha')
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Error Messages -->
                @if ($errors->any() && !$errors->has('captcha'))
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full bg-chaqueno-500 hover:bg-chaqueno-600 text-white font-semibold py-3 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center space-x-2">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Ingresar al Sistema</span>
                </button>
            </form>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="{{ url('/') }}" 
                   class="inline-flex items-center space-x-2 text-gray-600 hover:text-chaqueno-500 transition-colors duration-200 font-medium">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver al sitio web</span>
                </a>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="text-center">
            <p class="text-xs text-gray-500 flex items-center justify-center space-x-1">
                <i class="fas fa-lock"></i>
                <span>Conexión segura y protegida</span>
            </p>
        </div>
    </div>
</div>
@endsection



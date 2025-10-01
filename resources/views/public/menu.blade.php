@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-gray-50 to-slate-100">
    <!-- Hero Section -->
    <div class="relative bg-gradient-to-br from-amber-600 via-orange-700 to-red-800 text-white py-24 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-black/40 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl mb-6">
                <i class="fas fa-fire text-4xl text-orange-200"></i>
            </div>
            <h1 class="text-5xl md:text-6xl font-bold mb-6 bg-gradient-to-r from-white to-orange-100 bg-clip-text text-transparent">
                Menú Gourmet
            </h1>
            <p class="text-xl text-orange-100 max-w-3xl mx-auto leading-relaxed">
                Auténticas costillas de chancho a la cruz, preparadas con técnicas tradicionales chaqueñas y el sabor inigualable del fuego de leña
            </p>
        </div>
    </div>

    <!-- Menú Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        @php
            $productos = collect($productos ?? $platos ?? []);
        @endphp

        <!-- Título de Sección -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl mb-6 shadow-lg">
                <i class="fas fa-fire text-white text-2xl"></i>
            </div>
            <h2 class="text-4xl font-bold text-gray-900 mb-4">
                Costillas de Chancho a la Cruz
            </h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                Nuestro plato estrella preparado con técnicas ancestrales. Carne tierna y jugosa cocida lentamente al fuego de leña, 
                con el auténtico sabor ahumado que solo la tradición chaqueña puede ofrecer.
            </p>
        </div>
        
        <!-- Grid de Platos -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-8">
            @php
                $platos_chancho = [
                    ['cantidad' => '1', 'precio' => 50, 'titulo' => 'Individual', 'descripcion' => 'Perfecta para probar', 'popular' => false],
                    ['cantidad' => '1.5', 'precio' => 60, 'titulo' => 'Clásica', 'descripcion' => 'La porción favorita', 'popular' => true],
                    ['cantidad' => '2', 'precio' => 70, 'titulo' => 'Generosa', 'descripcion' => 'Para buen apetito', 'popular' => false],
                    ['cantidad' => '2.5', 'precio' => 80, 'titulo' => 'Abundante', 'descripcion' => 'Ideal para compartir', 'popular' => false],
                    ['cantidad' => '3', 'precio' => 90, 'titulo' => 'Familiar', 'descripcion' => 'Extra grande', 'popular' => false]
                ];
            @endphp
            
            @foreach($platos_chancho as $index => $plato)
            <div class="relative group">
                @if($plato['popular'])
                <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 z-10">
                    <span class="bg-gradient-to-r from-amber-500 to-orange-600 text-white px-4 py-1 rounded-full text-sm font-semibold shadow-lg">
                        ⭐ Más Pedida
                    </span>
                </div>
                @endif
                
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 {{ $plato['popular'] ? 'ring-2 ring-amber-400 ring-opacity-50' : '' }}">
                    <!-- Imagen/Header -->
                    <div class="relative h-48 bg-gradient-to-br from-amber-400 via-orange-500 to-red-600 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center text-white">
                                <i class="fas fa-fire text-4xl mb-2 drop-shadow-lg"></i>
                                <div class="text-2xl font-bold drop-shadow-lg">{{ $plato['cantidad'] }}</div>
                                <div class="text-sm opacity-90">{{ $plato['cantidad'] == '1' ? 'Costilla' : 'Costillas' }}</div>
                            </div>
                        </div>
                        
                        <!-- Badge de disponibilidad -->
                        <div class="absolute top-4 right-4">
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-lg">
                                <i class="fas fa-check mr-1"></i>Disponible
                            </span>
                        </div>
                    </div>
                    
                    <!-- Contenido -->
                    <div class="p-6">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 mb-1">{{ $plato['titulo'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $plato['descripcion'] }}</p>
                        </div>
                        
                        <!-- Precio -->
                        <div class="text-center mb-6">
                            <div class="text-3xl font-bold bg-gradient-to-r from-amber-600 to-orange-600 bg-clip-text text-transparent">
                                Bs. {{ $plato['precio'] }}
                            </div>
                        </div>
                        
                        <!-- Botón -->
                        <a href="{{ route('public.order') }}" 
                           class="w-full bg-gradient-to-r from-amber-500 to-orange-600 hover:from-amber-600 hover:to-orange-700 text-white py-3 px-4 rounded-xl font-semibold transition-all duration-300 flex items-center justify-center shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Pedir Ahora
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Información Adicional -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg">
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-leaf text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">100% Natural</h3>
                <p class="text-gray-600">Carne fresca sin conservantes, cocida al fuego de leña tradicional</p>
            </div>
            
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg">
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-clock text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Cocción Lenta</h3>
                <p class="text-gray-600">Preparado con paciencia durante horas para lograr la textura perfecta</p>
            </div>
            
            <div class="text-center p-8 bg-white rounded-2xl shadow-lg">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-white text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Receta Tradicional</h3>
                <p class="text-gray-600">Técnica ancestral chaqueña transmitida de generación en generación</p>
            </div>
        </div>



        <!-- Call to Action Final -->
        <div class="mt-20 bg-gradient-to-r from-amber-600 via-orange-600 to-red-700 rounded-3xl p-12 text-center text-white shadow-2xl">
            <div class="max-w-3xl mx-auto">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-fire text-3xl"></i>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold mb-4">¿Listo para la experiencia?</h2>
                <p class="text-xl text-orange-100 mb-8 leading-relaxed">
                    Haz tu pedido ahora y disfruta del auténtico sabor chaqueño. 
                    Cada costilla es una obra maestra culinaria preparada con pasión.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('public.order') }}" 
                       class="bg-white text-orange-700 px-8 py-4 rounded-xl font-bold hover:bg-orange-50 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <i class="fas fa-shopping-cart mr-2"></i>
                        Hacer Pedido Ahora
                    </a>
                    <a href="{{ route('public.contacto') }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-xl font-bold hover:bg-white hover:text-orange-700 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-phone mr-2"></i>
                        Contactar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
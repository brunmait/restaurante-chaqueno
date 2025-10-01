@extends('layouts.site')
@section('title', 'Rincón Chaqueño - Sabor Auténtico del Chaco')

@push('styles')
<style>
  .rating { color: #a0522d; }
  .chip { border: 1px solid #e5e7eb; border-radius: 9999px; padding: 0.5rem 1rem; font-size: 0.875rem; background: white; }
</style>
@endpush

@section('content')
@php
  $promos = $promos ?? collect();
  $featured = $featured ?? collect();
  $categories = $categories ?? collect();
@endphp

<!-- Breadcrumbs -->
<div class="text-sm text-slate-500 mb-4">
  <a href="#" class="hover:underline">América del Sur</a> ›
  <a href="#" class="hover:underline">Bolivia</a> ›
  <a href="#" class="hover:underline">La Paz Department</a> ›
  <a href="#" class="hover:underline">El Alto</a> ›
  <span class="text-slate-800">Rincón Chaqueño</span>
</div>

<!-- Hero Section -->
<div class="hero-cover shadow-xl mb-6">
  <img src="https://images.unsplash.com/photo-1544025162-d76694265947?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&h=500" alt="Rincón Chaqueño" class="w-full h-full object-cover">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <div class="flex flex-wrap items-end justify-between gap-6">
      <div>
        <h1 class="text-4xl font-bold mb-2">Rincón Chaqueño</h1>
        <div class="flex items-center gap-4 mb-3">
          <div class="rating flex">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            <svg class="w-5 h-5 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
          </div>
          <a href="#opiniones" class="text-white/80 text-sm hover:underline">(32 opiniones)</a>
          <span class="text-white/80 text-sm">N.º 23 de 500 restaurantes en El Alto</span>
          <span class="text-white/80 text-sm">Barbacoa, Asador</span>
        </div>
        <div class="flex flex-wrap gap-2">
          <span class="chip">🔥 Barbacoa</span>
          <span class="chip">🍖 Asador</span>
          <span class="chip">🥤 Bebidas</span>
        </div>
      </div>
      <div class="flex gap-3">
        <button class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg transition-colors">
          <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"/></svg>
          Compartir
        </button>
        <button class="bg-chaqueno-600 hover:bg-chaqueno-700 text-white px-4 py-2 rounded-lg transition-colors">
          <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/></svg>
          Guardar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Main Container with Tabs -->
<div x-data="{ activeTab: 'resumen' }">
  <!-- Sticky Navigation Tabs -->
  <div class="sticky-tabs">
    <div class="flex space-x-8 py-3">
      <button @click="activeTab = 'resumen'" :class="activeTab === 'resumen' ? 'border-chaqueno-600 text-chaqueno-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 pb-2 font-medium transition-colors">Resumen</button>
      <button @click="activeTab = 'menu'" :class="activeTab === 'menu' ? 'border-chaqueno-600 text-chaqueno-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 pb-2 font-medium transition-colors">Menú</button>
      <button @click="activeTab = 'opiniones'" :class="activeTab === 'opiniones' ? 'border-chaqueno-600 text-chaqueno-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 pb-2 font-medium transition-colors">Opiniones</button>
      <button @click="activeTab = 'ubicacion'" :class="activeTab === 'ubicacion' ? 'border-chaqueno-600 text-chaqueno-600' : 'border-transparent text-slate-500 hover:text-slate-700'" class="border-b-2 pb-2 font-medium transition-colors">Ubicación</button>
    </div>
  </div>

  <!-- Content Sections -->
  <div class="mt-8">
  <!-- Resumen -->
  <div x-show="activeTab === 'resumen'" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 space-y-6">
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Sabor auténtico del Chaco</h2>
        <p class="text-slate-600 leading-relaxed">
          Carnes a la parrilla con yuca, ensaladas frescas y postres tradicionales.
          Ingredientes seleccionados para una experiencia inolvidable que te transporta
          al corazón del Chaco boliviano.
        </p>
      </div>

      <!-- Galería -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-xl font-semibold mb-4">Fotos</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 gallery">
          <img class="w-full rounded-xl" src="{{ asset('images/chancho-0000.jpg') }}" alt="Chancho a la Cruz">
          <img class="w-full rounded-xl" src="{{ asset('images/pollo.jpg') }}" alt="Pollo a la Leña">
          <img class="w-full rounded-xl" src="{{ asset('images/fondo-restaurante.jpg') }}" alt="Ambiente del Restaurante">
          <img class="w-full rounded-xl" src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400" alt="Parrilla">
          <img class="w-full rounded-xl" src="https://images.unsplash.com/photo-1551782450-17144efb9c50?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400" alt="Carnes Asadas">
          <img class="w-full rounded-xl" src="https://images.unsplash.com/photo-1565958011703-44f9829ba187?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400" alt="Ambiente Familiar">
        </div>
      </div>
    </div>

    <div class="space-y-6">
      <!-- Info rápida -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-slate-600 uppercase tracking-wide">Información</h3>
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
            <span>El Alto – Bolivia</span>
          </div>
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/></svg>
            <span>Vie-Sáb-Dom y Feriados: 10:00-15:00</span>
          </div>
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
            <span>+591 63217872</span>
          </div>
        </div>
        <button @click="activeTab = 'ubicacion'" class="w-full mt-4 bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 text-white py-2 px-4 rounded-lg hover:from-chaqueno-700 hover:to-chaqueno-800 transition-all">
          Cómo llegar
        </button>
      </div>

      <!-- Acciones rápidas -->
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-slate-600 uppercase tracking-wide">Acciones</h3>
        <div class="space-y-3">
          <a href="{{ route('public.order') }}" class="block w-full bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 text-white py-3 px-4 rounded-lg hover:from-chaqueno-700 hover:to-chaqueno-800 transition-all text-center font-medium">
            🛒 Pedir Ahora
          </a>
          <a href="{{ route('public.menu') }}" class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 px-4 rounded-lg transition-colors text-center font-medium">
            📋 Ver Menú Completo
          </a>
          <a href="https://wa.me/59163217872" target="_blank" class="block w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition-colors text-center font-medium">
            📱 WhatsApp
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Menú -->
  <div x-show="activeTab === 'menu'" class="space-y-6">
    <div class="flex justify-between items-center">
      <h2 class="text-2xl font-bold">Menú destacado</h2>
      <a href="{{ route('public.menu') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg transition-colors">Ver Menú completo</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @for($i = 1; $i <= 6; $i++)
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <img src="https://images.unsplash.com/photo-{{ 1565299624946 + $i }}?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&h=400" class="w-full h-48 object-cover" alt="Plato {{ $i }}">
        <div class="p-4">
          <h3 class="font-semibold mb-1">Plato Especial {{ $i }}</h3>
          <p class="text-slate-500 text-sm mb-2">Categoría</p>
          <p class="text-slate-600 text-sm mb-3">Deliciosa preparación tradicional con ingredientes frescos...</p>
          <div class="flex justify-between items-center">
            <span class="font-bold text-lg">Bs {{ 45 + $i * 5 }}</span>
            <button class="bg-chaqueno-600 hover:bg-chaqueno-700 text-white px-4 py-2 rounded-lg transition-colors text-sm">Agregar</button>
          </div>
        </div>
      </div>
      @endfor
    </div>
  </div>

  <!-- Opiniones -->
  <div x-show="activeTab === 'opiniones'" class="space-y-6">
    <h2 class="text-2xl font-bold">Opiniones</h2>
    <div class="space-y-4">
      @for($i = 1; $i <= 3; $i++)
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-start justify-between mb-4">
          <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-r from-chaqueno-500 to-chaqueno-600 rounded-full flex items-center justify-center text-white font-bold">
              C{{ $i }}
            </div>
            <div>
              <div class="font-semibold">Cliente {{ $i }}</div>
              <div class="rating flex text-sm">
                @for($j = 1; $j <= 5; $j++)
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @endfor
              </div>
            </div>
          </div>
          <span class="text-slate-400 text-sm">hace {{ $i }} día{{ $i > 1 ? 's' : '' }}</span>
        </div>
        <p class="text-slate-600">Excelente comida y atención. Los platos están muy bien preparados y el ambiente es acogedor. Definitivamente volveré.</p>
      </div>
      @endfor
    </div>
  </div>

  <!-- Ubicación -->
  <div x-show="activeTab === 'ubicacion'" class="space-y-6">
    <h2 class="text-2xl font-bold">Ubicación</h2>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
          <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
          <div id="map" style="height: 400px;"></div>
        </div>
      </div>
      <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4 text-slate-600 uppercase tracking-wide">Cómo llegar</h3>
        <p class="mb-4 text-gray-600">Av. Principal 123, El Alto, Bolivia</p>
        <div class="mb-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
          <p class="text-sm text-blue-800">
            <i class="fas fa-info-circle mr-2"></i>
            Radio de entrega: 120 metros
          </p>
        </div>
        
        <!-- Botón para verificar ubicación -->
        <form id="formUbicacion" class="space-y-3">
          <input type="hidden" id="latitud" name="latitud">
          <input type="hidden" id="longitud" name="longitud">
          <input type="hidden" id="precision_m" name="precision_m">
          
          <button type="button" onclick="verificarUbicacion()" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg transition-colors font-medium">
            📍 Verificar mi ubicación
          </button>
          
          <div id="resultadoUbicacion" class="hidden p-3 rounded-lg text-sm"></div>
        </form>
        
        <div class="space-y-3 mt-4">
          <a href="https://wa.me/59163217872" target="_blank" class="block w-full bg-green-500 hover:bg-green-600 text-white py-3 px-4 rounded-lg transition-colors text-center font-medium">
            📱 WhatsApp
          </a>
          <button class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 py-3 px-4 rounded-lg transition-colors font-medium">
            📍 Dirección completa
          </button>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Configuración del mapa
const REF = { lat: -16.611740, lng: -68.183371 };
const RADIUS = 120;

// Inicializar mapa cuando se muestre la pestaña de ubicación
let map = null;
let userMarker = null;

function initMap() {
  if (map) return;
  
  map = L.map('map').setView([REF.lat, REF.lng], 18);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '© OpenStreetMap contributors'
  }).addTo(map);
  
  // Marcador del restaurante
  L.marker([REF.lat, REF.lng])
    .addTo(map)
    .bindPopup('🍽️ Rincón Chaqueño')
    .openPopup();
  
  // Círculo de radio de entrega
  L.circle([REF.lat, REF.lng], {
    radius: RADIUS,
    color: '#a0522d',
    fillColor: '#a0522d',
    fillOpacity: 0.2
  }).addTo(map).bindPopup('Zona de entrega (120m)');
}

// Verificar ubicación del usuario
function verificarUbicacion() {
  if (!navigator.geolocation) {
    mostrarResultado('Tu navegador no soporta GPS', 'error');
    return;
  }
  
  mostrarResultado('Obteniendo ubicación...', 'info');
  
  navigator.geolocation.getCurrentPosition(
    (pos) => {
      const lat = pos.coords.latitude;
      const lng = pos.coords.longitude;
      const precision = pos.coords.accuracy;
      
      // Calcular distancia
      const dist = calcularDistancia(lat, lng, REF.lat, REF.lng);
      
      // Actualizar campos ocultos
      document.getElementById('latitud').value = lat.toFixed(7);
      document.getElementById('longitud').value = lng.toFixed(7);
      document.getElementById('precision_m').value = precision ? precision.toFixed(1) : '';
      
      // Mostrar resultado
      if (dist <= RADIUS) {
        mostrarResultado(`✅ Estás dentro del área de entrega (${Math.round(dist)}m)`, 'success');
      } else {
        mostrarResultado(`❌ Fuera del área de entrega (${Math.round(dist)}m de ${RADIUS}m)`, 'error');
      }
      
      // Inicializar mapa si no existe
      initMap();
      
      // Agregar marcador del usuario
      if (userMarker) {
        map.removeLayer(userMarker);
      }
      userMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-blue.png',
          shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        })
      }).addTo(map).bindPopup('📍 Tu ubicación');
      
      // Centrar mapa para mostrar ambos puntos
      const group = new L.featureGroup([L.marker([REF.lat, REF.lng]), userMarker]);
      map.fitBounds(group.getBounds().pad(0.1));
    },
    (err) => {
      mostrarResultado('No se pudo obtener ubicación: ' + err.message, 'error');
    },
    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
  );
}

function mostrarResultado(mensaje, tipo) {
  const div = document.getElementById('resultadoUbicacion');
  div.className = `p-3 rounded-lg text-sm ${
    tipo === 'success' ? 'bg-green-50 border border-green-200 text-green-800' :
    tipo === 'error' ? 'bg-red-50 border border-red-200 text-red-800' :
    'bg-blue-50 border border-blue-200 text-blue-800'
  }`;
  div.textContent = mensaje;
  div.classList.remove('hidden');
}

function calcularDistancia(lat1, lon1, lat2, lon2) {
  const R = 6371000; // metros
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
    Math.sin(dLon/2) * Math.sin(dLon/2);
  return 2 * R * Math.asin(Math.sqrt(a));
}

// Inicializar mapa cuando se cambie a la pestaña de ubicación
document.addEventListener('DOMContentLoaded', function() {
  // Observar cambios en la pestaña activa
  const observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
        const ubicacionDiv = document.querySelector('[x-show="activeTab === \'ubicacion\'"]');
        if (ubicacionDiv && ubicacionDiv.style.display !== 'none') {
          setTimeout(initMap, 100);
        }
      }
    });
  });
  
  const ubicacionDiv = document.querySelector('[x-show="activeTab === \'ubicacion\'"]');
  if (ubicacionDiv) {
    observer.observe(ubicacionDiv, { attributes: true });
  }
});
</script>
@endsection
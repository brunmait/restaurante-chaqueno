<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurante El Chaqueño</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'chaqueno': {
                            50: '#fdf8f6',
                            100: '#f2e8e5',
                            200: '#eaddd7',
                            300: '#e0cfc5',
                            400: '#d2bab0',
                            500: '#a0522d',
                            600: '#8b4513',
                            700: '#6f3609',
                            800: '#5d2f02',
                            900: '#3e1f01',
                        },
                        'golden': {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-white" style="font-family: Inter, sans-serif;">
    <nav x-data="{ open: false }" class="bg-white shadow-lg border-b-2 border-chaqueno-500">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('public.home') }}" class="flex items-center space-x-2">
                        <i class="fas fa-utensils text-chaqueno-500 text-2xl"></i>
                        <span class="text-xl font-bold text-chaqueno-500">El Chaqueño</span>
                    </a>
                </div>
                
                <!-- Desktop Navigation -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('public.menu') }}" class="text-gray-700 hover:text-chaqueno-500 font-medium transition-colors duration-200">Menú</a>
                    <a href="{{ route('public.promociones') }}" class="text-gray-700 hover:text-chaqueno-500 font-medium transition-colors duration-200">Promociones</a>
                    <a href="{{ route('public.contacto') }}" class="text-gray-700 hover:text-chaqueno-500 font-medium transition-colors duration-200">Contacto</a>
                    <a href="{{ route('public.order') }}" class="bg-chaqueno-500 hover:bg-chaqueno-600 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200">Pedidos en Línea</a>
                    <a href="{{ route('login') }}" class="text-chaqueno-500 hover:text-chaqueno-600 font-medium transition-colors duration-200">
                        <i class="fas fa-user-shield mr-1"></i>Admin
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="text-gray-700 hover:text-chaqueno-500">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div x-show="open" x-transition class="md:hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="{{ route('public.menu') }}" class="block px-3 py-2 text-gray-700 hover:text-chaqueno-500 font-medium">Menú</a>
                <a href="{{ route('public.promociones') }}" class="block px-3 py-2 text-gray-700 hover:text-chaqueno-500 font-medium">Promociones</a>
                <a href="{{ route('public.contacto') }}" class="block px-3 py-2 text-gray-700 hover:text-chaqueno-500 font-medium">Contacto</a>
                <a href="{{ route('public.order') }}" class="block px-3 py-2 bg-chaqueno-500 text-white rounded-lg font-medium mx-3">Pedidos en Línea</a>
                <a href="{{ route('login') }}" class="block px-3 py-2 text-chaqueno-500 font-medium">Admin</a>
            </div>
        </div>
    </nav>
    
    <main class="min-h-screen">
        @if(session('status'))
            <div x-data="{ show: true }" x-show="show" x-transition class="bg-chaqueno-50 border-l-4 border-chaqueno-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-chaqueno-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-chaqueno-700">{{ session('status') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="text-chaqueno-400 hover:text-chaqueno-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="bg-red-50 border-l-4 border-red-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button @click="show = false" class="text-red-400 hover:text-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-utensils text-chaqueno-500 text-2xl"></i>
                        <span class="text-xl font-bold">El Chaqueño</span>
                    </div>
                    <p class="text-gray-400">Sabores auténticos del norte argentino en cada plato.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <div class="space-y-2 text-gray-400">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Dirección del restaurante</p>
                        <p><i class="fas fa-phone mr-2"></i>+54 123 456 789</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@elchaqueno.com</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Horarios</h3>
                    <div class="space-y-2 text-gray-400">
                        <p>Lunes a Viernes: 11:00 - 23:00</p>
                        <p>Sábados: 11:00 - 24:00</p>
                        <p>Domingos: 11:00 - 22:00</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Restaurante El Chaqueño. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>
<!-- Botón flotante WhatsApp -->
<a href="#" id="whatsapp-fab"
   class="fixed bottom-6 right-6 inline-flex items-center justify-center w-14 h-14 rounded-full shadow-lg z-40"
   style="background:#25D366" aria-label="WhatsApp">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="26" height="26" fill="#fff">
    <path d="M16 3C8.8 3 3 8.8 3 16c0 2.3.6 4.5 1.7 6.4L3 29l6.7-1.7C11.5 28.4 13.7 29 16 29c7.2 0 13-5.8 13-13S23.2 3 16 3z"/>
  </svg>
</a>

<!-- Modal bienvenida WhatsApp -->
<div id="wa-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
  <div class="bg-white rounded-2xl p-6 w-[92vw] max-w-md mx-4">
    <div class="text-center mb-4">
      <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" width="32" height="32" fill="#25D366">
          <path d="M16 3C8.8 3 3 8.8 3 16c0 2.3.6 4.5 1.7 6.4L3 29l6.7-1.7C11.5 28.4 13.7 29 16 29c7.2 0 13-5.8 13-13S23.2 3 16 3z"/>
        </svg>
      </div>
      <h3 class="text-lg font-semibold mb-2">¿Chateamos por WhatsApp?</h3>
      <p class="text-sm text-gray-600 mb-4">Desde WhatsApp podrás pedir, ver el menú y recibir tu QR de pago.</p>
    </div>
    <div class="flex gap-3">
      <button id="wa-go" class="flex-1 py-3 rounded-lg text-white font-medium" style="background:#25D366">
        📱 Abrir WhatsApp
      </button>
      <button id="wa-cancel" class="flex-1 py-3 rounded-lg border border-gray-300 text-gray-700 font-medium">
        Seguir en la web
      </button>
    </div>
  </div>
</div>

<script>
  (function(){
    // Solo mostrar WhatsApp modal en páginas de pedidos específicamente
    const currentPath = window.location.pathname;
    const isOrderPage = currentPath.includes('/pedido');
    const isAdminPage = currentPath.includes('/login') || currentPath.includes('/admin') || currentPath.includes('/cajero');
    
    if (isAdminPage) {
      // Ocultar modal y botón en páginas de admin
      document.getElementById('wa-modal').style.display = 'none';
      document.getElementById('whatsapp-fab').style.display = 'none';
      return;
    }
    
    const numero = '59163217872';
    const mensaje = encodeURIComponent('Hola 👋 Quiero hacer un pedido / ver menú / pagar por QR.');
    const waLink = `https://wa.me/${numero}?text=${mensaje}`;

    const go = () => { window.open(waLink, '_blank'); };

    document.getElementById('whatsapp-fab').addEventListener('click', (e)=>{ e.preventDefault(); go(); });

    document.getElementById('wa-go').addEventListener('click', ()=> {
      go();
      document.getElementById('wa-modal').style.display = 'none';
    });

    document.getElementById('wa-cancel').addEventListener('click', ()=> {
      document.getElementById('wa-modal').style.display = 'none';
    });

    // Solo mostrar modal y autoredirigir en páginas de pedidos
    if (isOrderPage) {
      setTimeout(()=> {
        if (document.getElementById('wa-modal').style.display !== 'none') {
          window.location.href = waLink;
        }
      }, 3000);
    } else {
      // En otras páginas (menú, inicio, etc.), ocultar el modal automáticamente
      document.getElementById('wa-modal').style.display = 'none';
    }
  })();
</script>

</body>
</html>

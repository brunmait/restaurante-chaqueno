<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Rincón Chaqueño')</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-cover { position: relative; height: 360px; border-radius: 1.25rem; overflow: hidden; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(0deg, rgba(0,0,0,0.6), rgba(0,0,0,0.2)); }
        .hero-content { position: absolute; left: 0; right: 0; bottom: 0; color: white; padding: 2rem; }
        .sticky-tabs { position: sticky; top: 0; z-index: 50; background: white; border-bottom: 1px solid #e5e7eb; }
        .gallery img { height: 160px; object-fit: cover; border-radius: 0.75rem; }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <a href="{{ route('public.home') }}" class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-slate-800">Rincón Chaqueño</span>
                </a>
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('public.home') }}" class="text-slate-600 hover:text-chaqueno-600 transition-colors">Inicio</a>
                    <a href="{{ route('public.menu') }}" class="text-slate-600 hover:text-chaqueno-600 transition-colors">Menú</a>
                    <a href="{{ route('public.order') }}" class="text-slate-600 hover:text-chaqueno-600 transition-colors">Pedidos Online</a>
                    <a href="{{ route('login') }}" class="text-slate-600 hover:text-chaqueno-600 transition-colors">Admin</a>
                    <a href="{{ route('public.order') }}" class="bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 text-white px-4 py-2 rounded-lg hover:from-chaqueno-700 hover:to-chaqueno-800 transition-all">Pedir Ahora</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-800 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-bold">Rincón Chaqueño</span>
                    </div>
                    <p class="text-slate-300">Sabor auténtico del Chaco boliviano con carnes a la parrilla y tradición familiar.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contacto</h3>
                    <div class="space-y-2 text-slate-300">
                        <p>📍 El Alto, Bolivia</p>
                        <p>📞 +591 63217872</p>
                        <p>🕒 Vie-Sáb-Dom y Feriados: 10:00-15:00</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-lg font-semibold mb-4">Enlaces</h3>
                    <div class="space-y-2">
                        <a href="{{ route('public.menu') }}" class="block text-slate-300 hover:text-white transition-colors">Menú</a>
                        <a href="{{ route('public.order') }}" class="block text-slate-300 hover:text-white transition-colors">Pedidos Online</a>
                        <a href="{{ route('login') }}" class="block text-slate-300 hover:text-white transition-colors">Admin Login</a>
                        <a href="#" class="block text-slate-300 hover:text-white transition-colors">Ubicación</a>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-700 mt-8 pt-8 text-center text-slate-400">
                <p>&copy; {{ date('Y') }} Rincón Chaqueño. Todos los derechos reservados.</p>
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



<script>
  (function(){
    const currentPath = window.location.pathname;
    const isAdminPage = currentPath.includes('/login') || currentPath.includes('/admin') || currentPath.includes('/cajero');
    
    if (isAdminPage) {
      // Ocultar botón en páginas de admin
      document.getElementById('whatsapp-fab').style.display = 'none';
      return;
    }
    
    const numero = '59163217872';
    const mensaje = encodeURIComponent('Hola 👋 Quiero hacer un pedido / ver menú / pagar por QR.');
    const waLink = `https://wa.me/${numero}?text=${mensaje}`;

    const go = () => { window.open(waLink, '_blank'); };

    document.getElementById('whatsapp-fab').addEventListener('click', (e)=>{ e.preventDefault(); go(); });
  })();
</script>

    @stack('scripts')
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Panel de Cajero') - El Chaqueño</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .sidebar-link {
            @apply flex items-center px-4 py-3 text-chaqueno-700 rounded-xl hover:bg-chaqueno-100 hover:text-chaqueno-800 transition-all duration-200 font-medium text-base;
        }
        .sidebar-link.active {
            @apply bg-gradient-to-r from-chaqueno-600 to-chaqueno-700 text-white shadow-lg;
        }
        .sidebar-section {
            @apply text-slate-600 text-sm uppercase tracking-wider px-4 py-3 font-semibold;
        }
        .sidebar-divider {
            @apply h-px bg-gradient-to-r from-transparent via-slate-300 to-transparent mx-4 my-4;
        }
    </style>
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
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-50 font-sans">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
        <!-- Sidebar -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-white via-slate-50 to-slate-100 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-xl border-r border-slate-200">
            <div class="flex items-center justify-center h-20 bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 border-b border-emerald-800">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-cash-register text-white text-2xl"></i>
                    </div>
                    <span class="text-white text-2xl font-bold tracking-wide">Panel Cajero</span>
                </div>
            </div>
            
            <nav class="mt-4 px-4 space-y-3">
                <!-- Principal -->
                <div>
                    <div class="flex items-center mb-2 px-1">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-home text-white text-xs"></i>
                        </div>
                        <span class="text-slate-800 font-semibold text-sm">Principal</span>
                    </div>
                    <a href="{{ route('cashier.dashboard') }}" class="flex items-center p-2 text-slate-700 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-all {{ request()->routeIs('cashier.dashboard') ? 'bg-blue-100 text-blue-700' : '' }}">
                        <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-md flex items-center justify-center mr-2">
                            <i class="fas fa-chart-pie text-white text-xs"></i>
                        </div>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                </div>
                
                <!-- Venta Directa -->
                <div>
                    <div class="flex items-center mb-2 px-1">
                        <div class="w-6 h-6 bg-gradient-to-br from-red-500 to-orange-600 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-fire text-white text-xs"></i>
                        </div>
                        <span class="text-slate-800 font-semibold text-sm">Venta Directa</span>
                    </div>
                    <a href="{{ route('cashier.costillas.index') }}" class="flex items-center p-2 text-slate-700 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all {{ request()->routeIs('cashier.costillas.*') ? 'bg-red-100 text-red-700' : '' }}">
                        <div class="w-6 h-6 bg-gradient-to-br from-red-500 to-orange-600 rounded-md flex items-center justify-center mr-2">
                            <i class="fas fa-bacon text-white text-xs"></i>
                        </div>
                        <span class="font-medium text-sm">Chancho a la Cruz</span>
                        <span class="ml-auto bg-green-200 text-green-800 px-1.5 py-0.5 rounded-full text-xs font-medium">Activo</span>
                    </a>
                </div>
                
                <!-- Pedidos Online -->
                <div>
                    <div class="flex items-center mb-2 px-1">
                        <div class="w-6 h-6 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center mr-2">
                            <i class="fas fa-globe text-white text-xs"></i>
                        </div>
                        <span class="text-slate-800 font-semibold text-sm">Pedidos Online</span>
                    </div>
                    <div class="space-y-1">
                        <a href="{{ route('cashier.pedidos.index') }}" class="flex items-center p-2 text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition-all {{ request()->routeIs('cashier.pedidos.*') ? 'bg-emerald-100 text-emerald-700' : '' }}">
                            <div class="w-5 h-5 bg-gradient-to-br from-blue-500 to-indigo-600 rounded flex items-center justify-center mr-2">
                                <i class="fas fa-shopping-cart text-white text-xs"></i>
                            </div>
                            <span class="font-medium text-xs">Pedidos en Línea</span>
                            <span class="ml-auto bg-emerald-200 text-emerald-800 px-1 py-0.5 rounded text-xs">Activo</span>
                        </a>
                        <a href="{{ route('public.pantalla-pedidos') }}" target="_blank" class="flex items-center p-2 text-slate-700 hover:text-purple-700 hover:bg-purple-50 rounded-lg transition-all">
                            <div class="w-5 h-5 bg-gradient-to-br from-purple-500 to-pink-600 rounded flex items-center justify-center mr-2">
                                <i class="fas fa-tv text-white text-xs"></i>
                            </div>
                            <span class="font-medium text-xs">Pantalla de Órdenes</span>
                            <span class="ml-auto bg-purple-200 text-purple-800 px-1 py-0.5 rounded text-xs">Activo</span>
                        </a>
                        <a href="{{ route('cashier.comprobantes.index') }}" class="flex items-center p-2 text-slate-700 hover:text-indigo-700 hover:bg-indigo-50 rounded-lg transition-all {{ request()->routeIs('cashier.comprobantes.*') ? 'bg-indigo-100 text-indigo-700' : '' }}">
                            <div class="w-5 h-5 bg-gradient-to-br from-indigo-500 to-purple-600 rounded flex items-center justify-center mr-2">
                                <i class="fas fa-receipt text-white text-xs"></i>
                            </div>
                            <span class="font-medium text-xs">Comprobantes</span>
                            <span class="ml-auto bg-indigo-200 text-indigo-800 px-1 py-0.5 rounded text-xs">Activo</span>
                        </a>
                    </div>
                </div>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white py-3 px-4 rounded-xl transition-all duration-200 flex items-center justify-center space-x-2 shadow-lg font-medium">
                        <div class="w-6 h-6 bg-white/20 rounded flex items-center justify-center">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                        </div>
                        <span class="text-sm">Cerrar Sesión</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top bar -->
            <header class="bg-white shadow-sm border-b border-slate-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-slate-600 hover:text-slate-900 p-2 rounded-lg hover:bg-slate-100 transition-colors">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-4">
                        <div class="text-base text-slate-600">
                            Cajero: <span class="font-semibold text-chaqueno-700">{{ auth()->user()->nombre ?? 'Usuario' }}</span>
                        </div>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-6">
                @if(session('status'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-chaqueno-50 border border-chaqueno-200 text-chaqueno-800 px-4 py-3 rounded-xl relative shadow-sm">
                        <span class="block sm:inline font-medium text-base">{{ session('status') }}</span>
                        <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-chaqueno-600 hover:text-chaqueno-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl relative shadow-sm">
                        <span class="block sm:inline font-medium text-base">{{ session('error') }}</span>
                        <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-red-600 hover:text-red-800">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Mobile sidebar overlay -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden" x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"></div>
</body>
</html>

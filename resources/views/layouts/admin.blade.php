<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Panel - Rincón Chaqueño</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .animate-slideInLeft {
            animation: slideInLeft 0.3s ease-out;
        }
        
        .sidebar-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 50%, #1e293b 100%);
        }
        
        .nav-item:hover {
            transform: translateX(4px);
        }
        
        .nav-item {
            transition: all 0.2s ease;
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
        <!-- Sidebar Elegante -->
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 shadow-2xl">
            <!-- Header Elegante -->
            <div class="relative h-16 bg-gradient-to-r from-chaqueno-600 via-chaqueno-700 to-chaqueno-800 border-b border-chaqueno-900/50">
                <div class="absolute inset-0 bg-gradient-to-r from-black/20 to-transparent"></div>
                <div class="relative flex items-center justify-center h-full px-4">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="w-8 h-8 bg-gradient-to-br from-amber-400 to-orange-500 rounded-lg flex items-center justify-center shadow-xl">
                                <i class="fas fa-utensils text-white text-sm"></i>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-white text-base font-bold tracking-wide">Rincón Chaqueño</h1>
                            <p class="text-chaqueno-200 text-xs font-medium">Panel de Administración</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <nav class="mt-6 px-4">
                <!-- Inicio -->
                <div class="mb-6">
                    <div class="text-slate-400 text-xs uppercase tracking-wider font-bold mb-3 flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-emerald-400 to-teal-500 rounded flex items-center justify-center mr-2">
                            <i class="fas fa-home text-white text-xs"></i>
                        </div>
                        Inicio
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-chaqueno-500 to-chaqueno-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.dashboard') ? 'bg-white/20' : 'bg-gradient-to-br from-blue-100 to-indigo-100 group-hover:from-blue-200 group-hover:to-indigo-200' }}">
                            <i class="fas fa-chart-pie text-sm {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-blue-600' }}"></i>
                        </div>
                        <span class="font-medium text-sm">Dashboard</span>
                    </a>
                </div>
                
                <!-- Gestión -->
                <div class="mb-6">
                    <div class="text-slate-400 text-xs uppercase tracking-wider font-bold mb-3 flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-purple-400 to-pink-500 rounded flex items-center justify-center mr-2">
                            <i class="fas fa-cogs text-white text-xs"></i>
                        </div>
                        Gestión
                    </div>
                    
                    <!-- Usuarios Dropdown -->
                    <div x-data="{ open: {{ request()->routeIs('admin.cajeros.*') ? 'true' : 'false' }} }" class="mb-2">
                        <button @click="open = !open" class="group w-full flex items-center justify-between px-3 py-2 rounded-lg transition-all duration-200 text-slate-600 hover:bg-slate-100 hover:text-slate-800">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-gradient-to-br from-purple-100 to-pink-100 group-hover:from-purple-200 group-hover:to-pink-200 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-purple-600 text-sm"></i>
                                </div>
                                <span class="font-medium text-sm">Usuarios</span>
                            </div>
                            <i class="fas fa-chevron-down transform transition-transform duration-200 text-xs" :class="open ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open" x-transition class="ml-11 mt-1 space-y-1">
                            <a href="{{ route('admin.cajeros.create') }}" class="flex items-center px-3 py-1.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.cajeros.create') ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                                <i class="fas fa-user-plus w-3 mr-2 text-xs {{ request()->routeIs('admin.cajeros.create') ? 'text-white' : 'text-purple-500' }}"></i>
                                <span class="text-xs font-medium">Registrar Cajero</span>
                            </a>
                            <a href="{{ route('admin.cajeros.index') }}" class="flex items-center px-3 py-1.5 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.cajeros.index') ? 'bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-md' : 'text-slate-600 hover:bg-slate-100' }}">
                                <i class="fas fa-list w-3 mr-2 text-xs {{ request()->routeIs('admin.cajeros.index') ? 'text-white' : 'text-purple-500' }}"></i>
                                <span class="text-xs font-medium">Lista de Cajeros</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Productos -->
                    <a href="{{ route('admin.productos.index') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.productos.*') ? 'bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.productos.*') ? 'bg-white/20' : 'bg-gradient-to-br from-amber-100 to-orange-100 group-hover:from-amber-200 group-hover:to-orange-200' }}">
                            <i class="fas fa-boxes text-sm {{ request()->routeIs('admin.productos.*') ? 'text-white' : 'text-amber-600' }}"></i>
                        </div>
                        <span class="font-medium text-sm">Productos</span>
                    </a>
                </div>
                
                <!-- Gestión de Costillas -->
                <div class="mb-6">
                    <div class="text-slate-400 text-xs uppercase tracking-wider font-bold mb-3 flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-red-400 to-pink-500 rounded flex items-center justify-center mr-2">
                            <i class="fas fa-fire text-white text-xs"></i>
                        </div>
                        Costillas
                    </div>
                    
                    <a href="{{ route('admin.costillas.index') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all duration-200 mb-2 {{ request()->routeIs('admin.costillas.*') ? 'bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.costillas.*') ? 'bg-white/20' : 'bg-gradient-to-br from-red-100 to-pink-100 group-hover:from-red-200 group-hover:to-pink-200' }}">
                            <i class="fas fa-bacon text-sm {{ request()->routeIs('admin.costillas.*') ? 'text-white' : 'text-red-600' }}"></i>
                        </div>
                        <span class="font-medium text-sm">Chancho a la Cruz</span>
                    </a>
                    
                    <a href="{{ route('admin.compras.index') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.compras.*') ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.compras.*') ? 'bg-white/20' : 'bg-gradient-to-br from-emerald-100 to-teal-100 group-hover:from-emerald-200 group-hover:to-teal-200' }}">
                            <i class="fas fa-shopping-cart text-sm {{ request()->routeIs('admin.compras.*') ? 'text-white' : 'text-emerald-600' }}"></i>
                        </div>
                        <span class="font-medium text-sm">Compras</span>
                    </a>
                </div>
                
                <!-- Reportes -->
                <div class="mb-6">
                    <div class="text-slate-400 text-xs uppercase tracking-wider font-bold mb-3 flex items-center">
                        <div class="w-4 h-4 bg-gradient-to-br from-indigo-400 to-purple-500 rounded flex items-center justify-center mr-2">
                            <i class="fas fa-chart-bar text-white text-xs"></i>
                        </div>
                        Reportes
                    </div>
                    
                    <a href="{{ route('admin.reportes.index') }}" class="group flex items-center px-3 py-2 rounded-lg transition-all duration-200 {{ request()->routeIs('admin.reportes.*') ? 'bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-lg' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-800' }}">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center mr-3 {{ request()->routeIs('admin.reportes.*') ? 'bg-white/20' : 'bg-gradient-to-br from-indigo-100 to-purple-100 group-hover:from-indigo-200 group-hover:to-purple-200' }}">
                            <i class="fas fa-file-alt text-sm {{ request()->routeIs('admin.reportes.*') ? 'text-white' : 'text-indigo-600' }}"></i>
                        </div>
                        <span class="font-medium text-sm">Ver Reportes</span>
                    </a>
                </div>
            </nav>
            
            <div class="absolute bottom-0 w-full p-4">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="group w-full bg-gradient-to-r from-red-500 to-rose-600 hover:from-red-600 hover:to-rose-700 text-white py-3 px-4 rounded-xl transition-all duration-300 flex items-center justify-center space-x-2 shadow-xl hover:shadow-2xl transform hover:scale-105 font-medium">
                        <div class="w-6 h-6 bg-white/20 rounded flex items-center justify-center group-hover:bg-white/30 transition-all">
                            <i class="fas fa-sign-out-alt text-sm"></i>
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
                            Bienvenido, <span class="font-semibold text-chaqueno-700">{{ auth()->user()->nombre ?? 'Admin' }}</span>
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
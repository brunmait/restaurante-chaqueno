<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Online - Restaurante El Chaqueño</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-slate-100 to-slate-200 min-h-screen">
    <div x-data="pedidoApp()" class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-700 rounded-2xl overflow-hidden mb-8 shadow-2xl">
            <div class="bg-gradient-to-r from-slate-900/70 to-slate-800/50 p-8">
                <div class="text-white text-center">
                    <h1 class="text-4xl font-bold mb-3 flex items-center justify-center">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-utensils text-2xl"></i>
                        </div>
                        Restaurante El Chaqueño
                    </h1>
                    <div class="text-emerald-100 text-xl font-medium">Pedidos en Línea</div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Menu -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h2 class="text-2xl font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-fire text-red-600 mr-3"></i>
                        Chancho a la Cruz
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <template x-for="(cantidad, precio) in precios" :key="precio">
                            <div class="bg-gradient-to-br from-red-50 to-orange-50 border-2 border-red-200 rounded-xl p-4 hover:shadow-md transition-all cursor-pointer"
                                 @click="agregarAlCarrito(cantidad, precio)">
                                <div class="flex justify-between items-center">
                                    <div>
                                        <div class="text-lg font-bold text-slate-800" x-text="cantidad + ' Costilla' + (cantidad != 1 ? 's' : '')"></div>
                                        <div class="text-sm text-slate-600">Chancho a la cruz</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-emerald-600" x-text="precio + ' Bs'"></div>
                                        <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-sm font-medium transition-colors">
                                            <i class="fas fa-plus mr-1"></i>Agregar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Carrito y Formulario -->
            <div class="space-y-6">
                <!-- Carrito -->
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-shopping-cart text-blue-600 mr-3"></i>
                        Tu Pedido
                    </h3>
                    
                    <div x-show="carrito.length === 0" class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-shopping-cart text-2xl text-slate-400"></i>
                        </div>
                        <p class="text-slate-500">Tu carrito está vacío</p>
                    </div>

                    <div x-show="carrito.length > 0" class="space-y-3">
                        <template x-for="(item, index) in carrito" :key="index">
                            <div class="bg-slate-50 rounded-lg p-3 border border-slate-200">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <div class="font-medium text-slate-800" x-text="item.cantidad + ' Costilla' + (item.cantidad != 1 ? 's' : '')"></div>
                                        <select x-model="item.acompanamiento" class="mt-2 text-sm border border-slate-300 rounded px-2 py-1 w-full">
                                            <option value="arroz">Con Arroz</option>
                                            <option value="mote">Con Mote</option>
                                            <option value="mixto">Mixto (Arroz + Mote)</option>
                                        </select>
                                    </div>
                                    <div class="text-right ml-3">
                                        <div class="font-bold text-emerald-600" x-text="item.precio + ' Bs'"></div>
                                        <button @click="eliminarDelCarrito(index)" class="text-red-600 hover:text-red-800 text-sm mt-1">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <div class="border-t border-slate-200 pt-3 mt-4">
                            <div class="flex justify-between items-center text-lg font-bold">
                                <span>Total:</span>
                                <span class="text-emerald-600" x-text="calcularTotal() + ' Bs'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Formulario -->
                <div x-show="carrito.length > 0" class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6">
                    <h3 class="text-xl font-bold text-slate-800 mb-4 flex items-center">
                        <i class="fas fa-user text-green-600 mr-3"></i>
                        Datos del Cliente
                    </h3>
                    
                    <form @submit.prevent="realizarPedido()" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Nombre completo</label>
                            <input x-model="cliente.nombre" type="text" required 
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Teléfono</label>
                            <input x-model="cliente.telefono" type="tel" required 
                                   class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-2">Notas adicionales (opcional)</label>
                            <textarea x-model="cliente.notas" rows="3" 
                                      class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"></textarea>
                        </div>
                        
                        <button type="submit" :disabled="enviando" 
                                class="w-full bg-gradient-to-r from-emerald-600 to-green-700 hover:from-emerald-700 hover:to-green-800 text-white py-3 px-4 rounded-lg font-medium transition-all disabled:opacity-50">
                            <span x-show="!enviando">
                                <i class="fas fa-paper-plane mr-2"></i>Realizar Pedido
                            </span>
                            <span x-show="enviando">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Enviando...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmación -->
    <div x-show="mostrarConfirmacion" x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md mx-4 shadow-2xl">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check text-2xl text-green-600"></i>
                </div>
                <h3 class="text-2xl font-bold text-slate-800 mb-2">¡Pedido Realizado!</h3>
                <p class="text-slate-600 mb-4">Tu número de ticket es:</p>
                <div class="text-4xl font-bold text-emerald-600 mb-4" x-text="numeroTicket"></div>
                <p class="text-sm text-slate-500 mb-6">Guarda este número para recoger tu pedido</p>
                <button @click="cerrarConfirmacion()" 
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2 rounded-lg font-medium">
                    Entendido
                </button>
            </div>
        </div>
    </div>

    <script>
        function pedidoApp() {
            return {
                precios: {
                    50: 1,
                    60: 1.5,
                    70: 2,
                    80: 2.5,
                    90: 3
                },
                carrito: [],
                cliente: {
                    nombre: '',
                    telefono: '',
                    notas: ''
                },
                enviando: false,
                mostrarConfirmacion: false,
                numeroTicket: '',

                agregarAlCarrito(cantidad, precio) {
                    this.carrito.push({
                        cantidad: cantidad,
                        precio: precio,
                        acompanamiento: 'arroz'
                    });
                },

                eliminarDelCarrito(index) {
                    this.carrito.splice(index, 1);
                },

                calcularTotal() {
                    return this.carrito.reduce((total, item) => total + item.precio, 0);
                },

                async realizarPedido() {
                    this.enviando = true;
                    
                    try {
                        const response = await fetch('/api/pedidos', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                cliente_nombre: this.cliente.nombre,
                                cliente_telefono: this.cliente.telefono,
                                items: this.carrito,
                                total: this.calcularTotal(),
                                notas: this.cliente.notas
                            })
                        });

                        const data = await response.json();
                        
                        if (data.success) {
                            this.numeroTicket = data.numero_ticket;
                            this.mostrarConfirmacion = true;
                            this.carrito = [];
                            this.cliente = { nombre: '', telefono: '', notas: '' };
                        } else {
                            alert('Error al realizar el pedido');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error al realizar el pedido');
                    } finally {
                        this.enviando = false;
                    }
                },

                cerrarConfirmacion() {
                    this.mostrarConfirmacion = false;
                }
            }
        }
    </script>
</body>
</html>
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


use App\Http\Controllers\PublicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\ReporteController;

Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/menu', [PublicController::class, 'menu'])->name('public.menu');
Route::get('/promociones', [PublicController::class, 'promociones'])->name('public.promociones');
Route::get('/contacto', [PublicController::class, 'contacto'])->name('public.contacto');
// Pedidos en línea
Route::get('/pedido', [PublicController::class, 'orderForm'])->name('public.order');
Route::post('/pedido', [PublicController::class, 'placeOrder'])->name('public.placeOrder');
Route::get('/pedido/{pedido}/pagar', [PublicController::class, 'pay'])->name('public.pay');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::get('/captcha', [AuthController::class, 'captcha'])->name('captcha');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Panel administrador (solo rol admin)
Route::middleware(['auth', 'role:admin', 'cache.headers:private;no_store'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    // Usuarios - Cajeros
    Route::get('/admin/cajeros/crear', [AdminController::class, 'createCashierForm'])->name('admin.cajeros.create');
    Route::get('/admin/cajeros', [AdminController::class, 'cajerosIndex'])->name('admin.cajeros.index');
    Route::post('/admin/cajeros', [AdminController::class, 'createCashier'])->name('admin.cajeros.store');
    Route::get('/admin/cajeros/{id}/editar', [AdminController::class, 'editCashierForm'])->name('admin.cajeros.edit');
    Route::put('/admin/cajeros/{id}', [AdminController::class, 'updateCashier'])->name('admin.cajeros.update');
    Route::delete('/admin/cajeros/{id}', [AdminController::class, 'deleteCashier'])->name('admin.cajeros.delete');
    Route::post('/admin/cajeros/{id}/toggle', function ($id) {
        \DB::table('usuarios')->where('id',$id)->update(['activo' => \DB::raw('NOT COALESCE(activo,1)')]);
        return back()->with('status','Estado actualizado');
    })->name('admin.cajeros.toggle');
    // Productos
    Route::get('/admin/productos', [AdminController::class, 'productsIndex'])->name('admin.productos.index');
    Route::post('/admin/productos', [AdminController::class, 'productsStore'])->name('admin.productos.store');
    // Costillas (nuevo sistema principal)
    Route::get('/admin/costillas', [CostillasController::class, 'adminIndex'])->name('admin.costillas.index');
    Route::post('/admin/costillas/agregar-stock', [CostillasController::class, 'agregarStock'])->name('admin.costillas.agregar-stock');
    
    // Sistema de Productos por Categorías
    Route::get('/admin/productos', [ProductosController::class, 'index'])->name('admin.productos.index');
    Route::get('/admin/productos/categoria/{id}', [ProductosController::class, 'categoria'])->name('admin.productos.categoria');
    Route::post('/admin/productos', [ProductosController::class, 'store'])->name('admin.productos.store');
    Route::put('/admin/productos/{producto}', [ProductosController::class, 'update'])->name('admin.productos.update');
    Route::delete('/admin/productos/{producto}', [ProductosController::class, 'destroy'])->name('admin.productos.destroy');
    Route::patch('/admin/productos/{producto}/toggle', [ProductosController::class, 'toggleDisponible'])->name('admin.productos.toggle');
    
    // Sistema de Compras
    Route::get('/admin/compras', [ComprasController::class, 'index'])->name('admin.compras.index');
    Route::post('/admin/compras', [ComprasController::class, 'store'])->name('admin.compras.store');
    Route::get('/admin/proveedores', [ComprasController::class, 'proveedores'])->name('admin.proveedores.index');
    Route::post('/admin/proveedores', [ComprasController::class, 'storeProveedor'])->name('admin.proveedores.store');
    // WhatsApp Test Panel
    Route::get('/admin/whatsapp', function() { return view('admin.whatsapp-test'); })->name('admin.whatsapp');
    
    // Reportes
    Route::get('/admin/reportes', [ReporteController::class, 'index'])->name('admin.reportes.index');
    Route::post('/admin/reportes/{id}/marcar-leido', [ReporteController::class, 'marcarComoLeido'])->name('admin.reportes.marcar-leido');
    Route::get('/admin/reportes/generar-mensual', [ReporteController::class, 'generarReporteMensual'])->name('admin.reportes.generar-mensual');
});

// Panel cajero (solo rol cajero)
Route::middleware(['auth', 'role:cajero', 'cache.headers:private;no_store'])->group(function () {
    Route::get('/cajero', [CashierController::class, 'dashboard'])->name('cashier.dashboard');
    Route::post('/cajero/sell', [CashierController::class, 'sell'])->name('cashier.sell');
    Route::get('/cajero/reprint', [CashierController::class, 'reprint'])->name('cashier.reprint');
    
    // Costillas (sistema principal)
    Route::get('/cajero/costillas', [CostillasController::class, 'cajeroIndex'])->name('cashier.costillas.index');
    Route::post('/cajero/costillas/vender', [CostillasController::class, 'vender'])->name('cashier.costillas.vender');
    
    // Pedidos en línea
    Route::post('/cajero/pedidos/{pedido}/aceptar', function ($pedido) {
        \DB::table('pedidos')->where('id', $pedido)->update(['estado' => 'preparando']);
        return back()->with('status', 'Pedido marcado como en preparación');
    })->name('cashier.orders.accept');
    Route::post('/cajero/pedidos/{pedido}/pagado', function ($pedido) {
        \DB::table('pedidos')->where('id', $pedido)->update(['estado_pago' => 'pagado']);
        return back()->with('status', 'Pago confirmado');
    })->name('cashier.orders.paid');
    Route::post('/cajero/pedidos/{pedido}/cancelar', function ($pedido) {
        \DB::table('pedidos')->where('id', $pedido)->update(['estado' => 'cancelado']);
        return back()->with('status', 'Pedido cancelado');
    })->name('cashier.orders.cancel');
});

// WhatsApp (requiere autenticación)
Route::middleware('auth')->group(function () {
    Route::post('/whatsapp/send-qr', [\App\Http\Controllers\WhatsAppController::class, 'sendPaymentQr'])->name('whatsapp.sendQr');
    Route::post('/whatsapp/send-menu', [\App\Http\Controllers\WhatsAppController::class, 'sendMenu'])->name('whatsapp.sendMenu');
});

// Pagos QR
use App\Http\Controllers\PagoController;
Route::post('/pagos/crear', [PagoController::class, 'crear'])->name('pagos.crear');
Route::get('/pagos/{uuid}', [PagoController::class, 'show'])->name('pagos.show');
Route::post('/pagos/{uuid}/marcar-pagado', [PagoController::class, 'marcarPagado'])->name('pagos.marcar-pagado');

// Sistema de Costillas
use App\Http\Controllers\CostillasController;
use App\Http\Controllers\PedidosOnlineController;
use App\Http\Controllers\ComprobantesController;

// Rutas del administrador para costillas
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/costillas', [CostillasController::class, 'adminIndex'])->name('admin.costillas.index');
    Route::post('/admin/costillas/agregar-stock', [CostillasController::class, 'agregarStock'])->name('admin.costillas.agregar-stock');
});

// Rutas del cajero para costillas
Route::middleware(['auth', 'role:cajero'])->group(function () {
    Route::get('/cajero/costillas', [CostillasController::class, 'cajeroIndex'])->name('cashier.costillas.index');
    Route::post('/cajero/costillas/vender', [CostillasController::class, 'vender'])->name('cashier.costillas.vender');
    
    // Pedidos en línea
    Route::get('/cajero/pedidos', [PedidosOnlineController::class, 'index'])->name('cashier.pedidos.index');
    Route::post('/cajero/pedidos/{id}/estado', [PedidosOnlineController::class, 'cambiarEstado'])->name('cashier.pedidos.estado');
    
    // Comprobantes
    Route::get('/cajero/comprobantes', [ComprobantesController::class, 'index'])->name('cashier.comprobantes.index');
    Route::get('/cajero/comprobantes/{id}', [ComprobantesController::class, 'show'])->name('cashier.comprobantes.show');
    Route::get('/cajero/comprobantes/{id}/qr', [ComprobantesController::class, 'qr'])->name('cashier.comprobantes.qr');
});

// API para pedidos online
Route::get('/api/costillas/stock', [CostillasController::class, 'stockDisponible'])->name('api.costillas.stock');

// Pantalla pública de pedidos
Route::get('/pantalla-pedidos', [PedidosOnlineController::class, 'pantallaPedidos'])->name('public.pantalla-pedidos');
Route::get('/pedido-online', function() { return view('public.pedido-online'); })->name('public.pedido-online');

// API para crear pedidos online
Route::post('/api/pedidos', [PedidosOnlineController::class, 'crear'])->name('api.pedidos.crear');

// Comprobantes públicos
Route::get('/comprobante/{ticket}', [ComprobantesController::class, 'publicShow'])->name('comprobantes.public');

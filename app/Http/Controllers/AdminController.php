<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Reporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ReporteController;

class AdminController extends Controller
{
    public function dashboard()
    {
        $roleNameCol = Schema::hasColumn('roles', 'nombre_rol') ? 'nombre_rol' : 'name';
        $cajeroRoleId = Role::where($roleNameCol, 'cajero')->value('id');

        // where('rol_id', ?) con bindings -> protege de inyección SQL
        $cajeros = User::with('role')
            ->when($cajeroRoleId, function ($q) use ($cajeroRoleId) {
                $q->where('rol_id', $cajeroRoleId);
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->get();

        // Generar reportes automáticos
        $reporteController = new ReporteController();
        $reporteController->generarReporteDiario();
        
        // Obtener reportes no leídos
        $reportesNoLeidos = Reporte::where('leido', false)
                                  ->orderBy('created_at', 'desc')
                                  ->limit(5)
                                  ->get();
        
        // Obtener estadísticas del día
        $ventasHoy = DB::table('ventas_costillas')
                      ->whereDate('created_at', today())
                      ->count();
        
        $stockActual = DB::table('stock_costillas')->value('costillas_disponibles') ?? 0;

        return view('admin.dashboard', compact('cajeros', 'reportesNoLeidos', 'ventasHoy', 'stockActual'));
    }

    public function cajerosIndex()
    {
        $roleNameCol = Schema::hasColumn('roles', 'nombre_rol') ? 'nombre_rol' : 'name';
        $cajeroRoleId = Role::where($roleNameCol, 'cajero')->value('id');

        $cajeros = User::with('role')
            ->when($cajeroRoleId, function ($q) use ($cajeroRoleId) {
                $q->where('rol_id', $cajeroRoleId);
            }, function ($q) {
                $q->whereRaw('1 = 0');
            })
            ->get();

        return view('admin.cajeros.panel', compact('cajeros'));
    }

    public function createCashierForm()
    {
        return view('admin.cajeros.create');
    }

    public function createCashier(Request $request)
    {
        // Validación de inputs antes de tocar la BD
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[^<>]+$/'],
            'email' => ['required', 'string', 'max:255', 'unique:usuarios,email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'name.regex' => 'El nombre contiene caracteres no permitidos (ej. <, >).',
        ]);

        $roleNameCol = Schema::hasColumn('roles', 'nombre_rol') ? 'nombre_rol' : 'name';
        $cajeroRoleId = Role::where($roleNameCol, 'cajero')->value('id');
        if (!$cajeroRoleId) {
            $cajeroRoleId = Role::insertGetId([
                $roleNameCol => 'cajero',
                (Schema::hasColumn('roles', 'descripcion') ? 'descripcion' : 'description') => 'Acceso a caja y ventas',
            ]);
        }

        // Inserción con arreglo asociativo -> parámetros preparados en Query Builder
        User::create([
            'nombre' => $request->name,
            'email' => $request->email,
            'contraseña' => Hash::make($request->password),
            'rol_id' => $cajeroRoleId,
            'activo' => $request->has('activo') ? true : false,
        ]);

        return redirect()->route('admin.cajeros.index')->with('status', 'Cajero creado correctamente');
    }

    public function editCashierForm($id)
    {
        $cajero = User::findOrFail($id);
        return view('admin.cajeros.edit', compact('cajero'));
    }

    public function updateCashier(Request $request, $id)
    {
        $cajero = User::findOrFail($id);
        
        // Validación de inputs antes de tocar la BD
        $rules = [
            'name' => ['required', 'string', 'max:255', 'regex:/^[^<>]+$/'],
            'email' => ['required', 'string', 'max:255', 'unique:usuarios,email,' . $id],
        ];
        
        if ($request->filled('password')) {
            $rules['password'] = ['min:6', 'confirmed'];
        }
        
        $request->validate($rules, [
            'name.regex' => 'El nombre contiene caracteres no permitidos (ej. <, >).',
        ]);

        $updateData = [
            'nombre' => $request->name,
            'email' => $request->email,
            'activo' => $request->has('activo') ? true : false,
        ];

        if ($request->filled('password')) {
            $updateData['contraseña'] = Hash::make($request->password);
        }

        // Actualización con arreglo asociativo -> parámetros preparados en Query Builder
        $cajero->update($updateData);

        return redirect()->route('admin.cajeros.index')->with('status', 'Cajero actualizado correctamente');
    }

    public function deleteCashier($id)
    {
        $cajero = User::findOrFail($id);
        $cajero->delete();

        return redirect()->route('admin.cajeros.index')->with('status', 'Cajero eliminado correctamente');
    }

    // Productos (admin)
    public function productsIndex()
    {
        // Asegurar que existan los insumos base (carne de chancho y pollo entero)
        $stockController = new \App\Http\Controllers\StockController();
        $stockController->getChanchoProductId();
        $stockController->getPolloProductId();

        $productos = DB::table('productos')->orderBy('nombre')->get();
        
        // Calcular ganancias del día
        $ganancias = $stockController->getGanancias();
        
        return view('admin.productos', compact('productos', 'ganancias'));
    }

    public function productsStore(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required','string','max:100','regex:/^[^<>]+$/'],
            'precio' => ['required','numeric','min:0'],
            'categoria' => ['required','string','max:50'],
            'descripcion' => ['nullable','string','max:300','regex:/^[^<>]*$/'],
            'stock_inicial' => ['nullable','integer','min:0'],
        ]);

        $productoId = DB::table('productos')->insertGetId([
            'nombre' => $data['nombre'],
            'precio' => $data['precio'],
            'categoria' => $data['categoria'],
            'descripcion' => $data['descripcion'] ?? null,
            'stock' => $data['stock_inicial'] ?? 0,
            'imagen' => '',
        ]);
        
        // Si es refresco y tiene stock inicial, registrar entrada en stock
        if ($data['categoria'] === 'refresco' && ($data['stock_inicial'] ?? 0) > 0) {
            DB::table('stock')->insert([
                'producto_id' => $productoId,
                'tipo' => 'entrada',
                'cantidad' => $data['stock_inicial'],
                'precio_unitario' => 0, // Se puede ajustar después
                'observaciones' => 'Stock inicial del producto',
                'fecha' => now(),
                'usuario_id' => auth()->id(),
            ]);
        }

        return back()->with('status','Producto registrado con éxito');
    }
}

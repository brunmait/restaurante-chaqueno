<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    public function index()
    {
        $categorias = Categoria::with('productos')->where('activo', true)->get();
        return view('admin.productos.index', compact('categorias'));
    }

    public function categoria($id)
    {
        $categoria = Categoria::with('productos')->findOrFail($id);
        return view('admin.productos.categoria', compact('categoria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'categoria_id' => 'required|exists:categorias,id',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string'
        ]);

        Producto::create($request->all());

        return redirect()->back()->with('success', 'Producto agregado exitosamente');
    }

    public function update(Request $request, Producto $producto)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'descripcion' => 'nullable|string'
        ]);

        $producto->update($request->all());

        return redirect()->back()->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->back()->with('success', 'Producto eliminado exitosamente');
    }

    public function toggleDisponible(Producto $producto)
    {
        $producto->update(['disponible' => !$producto->disponible]);
        return redirect()->back()->with('success', 'Estado actualizado');
    }
}
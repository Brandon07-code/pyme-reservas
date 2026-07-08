<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = Product::with('category')->search($search)->latest()->paginate(10);

        $total = Product::count();
        $activos = Product::where('estado', 1)->count();
        $inactivos = Product::where('estado', 0)->count();

        return view('products.index', compact('products', 'search', 'total', 'activos', 'inactivos'));
    }

    public function create()
    {
        $categories = ProductCategory::where('estado', true)->get();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'nombre' => 'required|string|max:150',
            'marca' => 'nullable|string|max:100', // NUEVO CAMPO
            'genero' => 'nullable|string|max:50', // NUEVO CAMPO
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'imagen_url' => 'nullable|string|max:255', // NUEVO CAMPO
            'estado' => 'boolean'
        ]);

        Product::create($validated);
        return redirect()->route('productos.index')->with('success', 'Producto creado correctamente.');
    }

    public function edit(Product $producto)
    {
        $categories = ProductCategory::where('estado', true)->get();
        return view('products.edit', compact('producto', 'categories'));
    }

    public function update(Request $request, Product $producto)
    {
        $validated = $request->validate([
            'product_category_id' => 'required|exists:product_categories,id',
            'nombre' => 'required|string|max:150',
            'marca' => 'nullable|string|max:100', // NUEVO CAMPO
            'genero' => 'nullable|string|max:50', // NUEVO CAMPO
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'imagen_url' => 'nullable|string|max:255', // NUEVO CAMPO
            'estado' => 'boolean'
        ]);

        $producto->update($validated);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $producto)
    {
        $nuevoEstado = !$producto->estado;
        $producto->update(['estado' => $nuevoEstado]);
        $mensaje = $nuevoEstado ? 'Producto activado correctamente.' : 'Producto desactivado correctamente.';
        return redirect()->route('productos.index')->with('success', $mensaje);
    }
}
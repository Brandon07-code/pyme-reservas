<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
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
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
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
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'estado' => 'boolean'
        ]);

        $producto->update($validated);
        return redirect()->route('productos.index')->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Product $producto)
    {
        $producto->update(['estado' => false]);
        return redirect()->route('productos.index')->with('success', 'Producto desactivado correctamente.');
    }
}
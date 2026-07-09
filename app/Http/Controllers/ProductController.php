<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // IMPORTACIÓN CORREGIDA
use Illuminate\Support\Str; // IMPORTACIÓN CORREGIDA

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $products = Product::with('category')->search($search)->latest()->paginate(12); // Paginación ajustada a 12 para la cuadrícula

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
            'marca' => 'nullable|string|max:100',
            'genero' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado' => 'boolean'
        ]);

        if ($request->hasFile('imagen_url')) {
            $file = $request->file('imagen_url');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $fileName, 'public'); 
            $validated['imagen_url'] = 'storage/' . $path;
        }

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
            'marca' => 'nullable|string|max:100',
            'genero' => 'nullable|string|max:50',
            'descripcion' => 'nullable|string|max:255',
            'precio' => 'required|numeric|min:0',
            'stock_actual' => 'required|integer|min:0',
            'imagen_url' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'estado' => 'boolean'
        ]);

        if ($request->hasFile('imagen_url')) {
            if ($producto->imagen_url && Storage::disk('public')->exists(str_replace('storage/', '', $producto->imagen_url))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $producto->imagen_url));
            }

            $file = $request->file('imagen_url');
            $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('products', $fileName, 'public');
            $validated['imagen_url'] = 'storage/' . $path;
        }

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
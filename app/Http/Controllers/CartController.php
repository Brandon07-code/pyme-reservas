<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Mostrar el carrito
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('portal.cart', compact('cart', 'total'));
    }

    // Agregar producto al carrito
    public function add(Request $request)
    {
        $producto = Product::findOrFail($request->product_id);

        if ($producto->estado == 0 || $producto->stock_actual <= 0) {
            return redirect()->back()->withErrors('El producto no está disponible.');
        }

        $cart = session()->get('cart', []);

        // Si ya está en el carrito, aumentamos la cantidad validando el stock
        if (isset($cart[$producto->id])) {
            if ($cart[$producto->id]['cantidad'] < $producto->stock_actual) {
                $cart[$producto->id]['cantidad']++;
            } else {
                return redirect()->back()->withErrors('No hay más stock disponible de ' . $producto->nombre);
            }
        } else {
            // Si no está, lo agregamos
            $cart[$producto->id] = [
                "nombre" => $producto->nombre,
                "marca" => $producto->marca,
                "cantidad" => 1,
                "precio" => $producto->precio,
                "imagen_url" => $producto->imagen_url,
                "stock_maximo" => $producto->stock_actual
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Producto agregado al carrito.');
    }

    // Quitar producto del carrito
    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->route('portal.cart.index')->with('success', 'Producto eliminado del carrito.');
        }
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['precio'] * $item['cantidad'];
        }

        return view('portal.cart', compact('cart', 'total'));
    }

    public function add(Request $request)
    {
        $producto = Product::findOrFail($request->product_id);
        $cantidadSolicitada = (int) $request->input('cantidad', 1);

        if ($producto->estado == 0 || $producto->stock_actual <= 0) {
            return redirect()->back()->withErrors('El producto no está disponible en este momento.');
        }

        if ($cantidadSolicitada > $producto->stock_actual) {
            return redirect()->back()->withErrors("Solo nos quedan {$producto->stock_actual} unidades de {$producto->nombre}.");
        }

        if ($cantidadSolicitada > 20) {
            return redirect()->back()->withErrors("El límite de reserva es de 20 unidades por pedido.");
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$producto->id])) {
            $nuevaCantidad = $cart[$producto->id]['cantidad'] + $cantidadSolicitada;
            
            if ($nuevaCantidad > $producto->stock_actual) {
                return redirect()->back()->withErrors("Ya tienes {$cart[$producto->id]['cantidad']} en el carrito. Solo quedan {$producto->stock_actual} unidades en total.");
            }
            if ($nuevaCantidad > 20) {
                return redirect()->back()->withErrors("No puedes llevar más de 20 unidades del mismo producto.");
            }
            
            $cart[$producto->id]['cantidad'] = $nuevaCantidad;
        } else {
            $cart[$producto->id] = [
                "nombre" => $producto->nombre,
                "marca" => $producto->marca,
                "cantidad" => $cantidadSolicitada,
                "precio" => $producto->precio,
                "imagen_url" => $producto->imagen_url,
                "stock_maximo" => $producto->stock_actual
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', "{$cantidadSolicitada}x {$producto->nombre} agregado al carrito.");
    }

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
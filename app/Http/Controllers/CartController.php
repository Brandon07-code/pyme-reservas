<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

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
            return redirect()->route('portal.index')->withErrors('El producto no está disponible en este momento.')->withFragment('seccion-perfumeria');
        }

        if ($cantidadSolicitada > $producto->stock_actual) {
            return redirect()->route('portal.index')->withErrors("Solo nos quedan {$producto->stock_actual} unidades de {$producto->nombre}.")->withFragment('seccion-perfumeria');
        }

        if ($cantidadSolicitada > 20) {
            return redirect()->route('portal.index')->withErrors("El límite de reserva es de 20 unidades por pedido.")->withFragment('seccion-perfumeria');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$producto->id])) {
            $nuevaCantidad = $cart[$producto->id]['cantidad'] + $cantidadSolicitada;
            
            if ($nuevaCantidad > $producto->stock_actual) {
                return redirect()->route('portal.index')->withErrors("Ya tienes {$cart[$producto->id]['cantidad']} en el carrito. Solo quedan {$producto->stock_actual} unidades en total.")->withFragment('seccion-perfumeria');
            }
            if ($nuevaCantidad > 20) {
                return redirect()->route('portal.index')->withErrors("No puedes llevar más de 20 unidades del mismo producto.")->withFragment('seccion-perfumeria');
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

        // FIX: Forzamos la redirección exacta con el anclaje
        return redirect()->route('portal.index')->with('success', "{$cantidadSolicitada}x {$producto->nombre} agregado al carrito.")->withFragment('seccion-perfumeria');
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

    public function checkout()
    {
        $cart = session()->get('cart');

        if (!$cart || count($cart) == 0) {
            return redirect()->route('portal.cart.index')->withErrors('Tu carrito está vacío.');
        }

        try {
            DB::beginTransaction();

            $totalPedido = 0;
            $clienteId = auth()->user()->client->id;

            $order = Order::create([
                'client_id' => $clienteId,
                'estado' => 'pendiente_recogida',
                'total' => 0 
            ]);

            foreach ($cart as $id => $details) {
                $producto = Product::lockForUpdate()->findOrFail($id); 

                if ($producto->stock_actual < $details['cantidad']) {
                    throw new \Exception("Lo sentimos, el producto '{$producto->nombre}' acaba de agotarse o no tiene stock suficiente.");
                }

                $producto->decrement('stock_actual', $details['cantidad']);

                $order->products()->attach($id, [
                    'cantidad' => $details['cantidad'],
                    'precio_historico' => $details['precio']
                ]);

                $totalPedido += ($details['precio'] * $details['cantidad']);
            }

            $order->update(['total' => $totalPedido]);

            session()->forget('cart');
            DB::commit();

            return redirect()->route('portal.index')->with('success', '¡Pedido confirmado! Hemos separado tus productos. Recuerda que tienes 24 horas para recogerlos y pagarlos en la barbería.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('portal.cart.index')->withErrors($e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $estadoFilter = $request->get('estado');

        $query = Order::with(['client', 'products'])->latest();

        if ($estadoFilter) {
            $query->where('estado', $estadoFilter);
        }

        $orders = $query->paginate(10);

        // Estadísticas rápidas para las Tarjetas
        $total = Order::count();
        $pendientes = Order::where('estado', 'pendiente_recogida')->count();
        $entregados = Order::where('estado', 'entregado')->count();

        return view('orders.index', compact('orders', 'estadoFilter', 'total', 'pendientes', 'entregados'));
    }

    public function update(Request $request, Order $pedido) // Cambiamos $order por $pedido para que coincida con la ruta
    {
        $request->validate(['estado' => 'required|in:pendiente_recogida,entregado,cancelado']);

        // Si se cancela, devolvemos el stock a la vitrina (A nivel de BD)
        if ($request->estado == 'cancelado' && $pedido->estado == 'pendiente_recogida') {
            foreach ($pedido->products as $producto) {
                $producto->increment('stock_actual', $producto->pivot->cantidad);
            }
        }

        $pedido->update(['estado' => $request->estado]);
        
        $mensaje = $request->estado == 'entregado' ? '¡Pedido entregado y pagado!' : 'Pedido cancelado. El stock fue devuelto.';
        return redirect()->route('orders.index')->with('success', $mensaje);
    }
}
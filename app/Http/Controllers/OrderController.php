<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $estadoFilter = $request->get('estado');
        $pedidoIdFilter = $request->get('pedido_id');

        $query = Order::with(['client', 'products'])->latest();

        if ($estadoFilter) {
            $query->where('estado', $estadoFilter);
        }

        if ($pedidoIdFilter) {
            $query->where('id', $pedidoIdFilter);
        }

        $orders = $query->paginate(10);

        // Estadísticas rápidas para las 4 Tarjetas
        $total = Order::count();
        $nuevos = Order::where('estado', 'pendiente')->count();
        $pendientes = Order::where('estado', 'pendiente_recogida')->count();
        $entregados = Order::where('estado', 'entregado')->count();

        return view('orders.index', compact('orders', 'estadoFilter', 'total', 'nuevos', 'pendientes', 'entregados'));
    }

    public function update(Request $request, Order $pedido) 
    {
        // Permitimos los 4 estados
        $request->validate(['estado' => 'required|in:pendiente,pendiente_recogida,entregado,cancelado']);

        // Si se cancela, devolvemos el stock a la vitrina
        if ($request->estado == 'cancelado' && in_array($pedido->estado, ['pendiente', 'pendiente_recogida'])) {
            foreach ($pedido->products as $producto) {
                $producto->increment('stock_actual', $producto->pivot->cantidad);
            }
        }

        $pedido->update(['estado' => $request->estado]);

        if (in_array($request->estado, ['cancelado', 'entregado'])) {
            auth()->user()->unreadNotifications
                ->where('data.tipo', 'pedido')
                ->where('data.pedido_id', $pedido->id)
                ->each->markAsRead();
        }
        
        // Mensaje dinámico según la acción del admin
        if ($request->estado == 'pendiente_recogida') {
            $mensaje = 'Paquete marcado como listo. El cliente puede pasar a recogerlo.';
            
            // Notificar al cliente
            if ($pedido->client && $pedido->client->user) {
                $pedido->client->user->notify(new \App\Notifications\PedidoAprobadoNotification($pedido));
            }
        } elseif ($request->estado == 'entregado') {
            $mensaje = 'Pedido entregado y cobrado en caja.';
        } else {
            $mensaje = 'Pedido cancelado. El stock fue devuelto a la vitrina.';
        }

        return redirect()->route('orders.index')->with('success', $mensaje);
    }

    public function exportPdf(Request $request)
    {
        ini_set('max_execution_time', 120);
        $estadoFilter = $request->get('estado');
        $query = Order::with(['client', 'products'])->latest();
        if ($estadoFilter) {
            $query->where('estado', $estadoFilter);
        }
        $orders = $query->get();
        $pdf = Pdf::loadView('pdf.pedidos', compact('orders'))->setPaper('a4', 'landscape');
        return $pdf->download('reporte-pedidos-jym-' . date('Y-m-d') . '.pdf');
    }
}
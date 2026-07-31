<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\EstadoPedido;

class OrderController extends Controller
{
    /**
     * Listado de órdenes
     */
    public function index(Request $request)
    {
        $q = trim($request->get('q', ''));
        $status = $request->get('status');

        $orders = Order::with([
                'user',
                'estadoPedido',
            ])
            ->when($q, function ($query) use ($q) {

                $query->where('numero_pedido', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($q2) use ($q) {

                        $q2->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");

                    });

            })
            ->when($status, function ($query) use ($status) {

                $query->whereHas('estadoPedido', function ($q) use ($status) {

                    $q->where('codigo', $status);

                });

            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $statuses = EstadoPedido::where('status', true)
            ->orderBy('id')
            ->pluck('codigo');

        $statusLabel = EstadoPedido::where('status', true)
            ->pluck('nombre', 'codigo')
            ->toArray();

        return view('admin.orders.index', compact(
            'orders',
            'q',
            'status',
            'statuses',
            'statusLabel'
        ));
    }
    /**
     * Detalle de orden
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'estadoPedido',
            'items.product',
            'shippingAddress',
        ]);

        $items = $order->items;

        $totals = [
            'items_subtotal' => $items->sum('subtotal'),
            'order_total' => $order->total_price,
        ];

        return view('admin.orders.show', compact(
            'order',
            'items',
            'totals'
        ));
    }

    /**
     * Actualizar estado
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'estado_pedido_id' => 'required|exists:estados_pedido,id',
        ]);

        if ($order->estado_pedido_id == $request->estado_pedido_id) {
            return back()->with('info', 'La orden ya tiene ese estado.');
        }

        $order->update([
            'estado_pedido_id' => $request->estado_pedido_id
        ]);

        return back()->with('success', 'Estado actualizado correctamente.');
    }
}
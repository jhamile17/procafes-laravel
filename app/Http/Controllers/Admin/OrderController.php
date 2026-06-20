<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    /**
     * Listado de órdenes
     */
    public function index(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $status = $request->get('status');

        $orders = DB::table('orders as o')
            ->join('users as u', 'u.id', '=', 'o.user_id')
            ->select(
                'o.id',
                'o.status',
                'o.total_price',
                'o.created_at',
                'u.name as customer_name',
                'u.email as customer_email'
            )
            ->when($q !== '', function ($query) use ($q) {
                $like = "%{$q}%";

                $query->where(function ($w) use ($like) {
                    $w->where('u.name', 'like', $like)
                      ->orWhere('u.email', 'like', $like)
                      ->orWhere('o.id', 'like', $like);
                });
            })
            ->when($status, function ($query) use ($status) {
                $query->where('o.status', $status);
            })
            ->orderByDesc('o.created_at')
            ->paginate(12)
            ->withQueryString();

        $statuses = DB::table('orders')
            ->select('status')
            ->distinct()
            ->pluck('status');

        $statusMap = Order::statusMap();

        return view('admin.orders.index', compact(
            'orders',
            'statuses',
            'status',
            'q',
            'statusMap'
        ));
    }

    /**
     * Detalle de orden
     */
    public function show($id)
    {
        $order = DB::table('orders as o')
            ->leftJoin('users as u', 'u.id', '=', 'o.user_id')
            ->select(
                'o.*',
                'u.name as customer_name',
                'u.email as customer_email'
            )
            ->where('o.id', $id)
            ->first();

        if (!$order) {
            return redirect()
                ->route('admin.orders.index')
                ->with('warning', 'La orden no existe.');
        }

        $items = DB::table('order_items as oi')
            ->join('products as p', 'p.id', '=', 'oi.product_id')
            ->select(
                'oi.id',
                'p.name as product_name',
                'oi.quantity',
                'oi.unit_price',
                'oi.subtotal'
            )
            ->where('oi.order_id', $id)
            ->orderBy('oi.id')
            ->get();

        $totals = [
            'items_subtotal' => (float) $items->sum('subtotal'),
            'order_total' => (float) $order->total_price,
        ];

        $statusMap = Order::statusMap();

        $statusLabel = $statusMap[$order->status]
            ?? ucfirst($order->status);

        return view('admin.orders.show', compact(
            'order',
            'items',
            'totals',
            'statusLabel'
        ));
    }

    /**
     * Actualizar estado
     */
    public function updateStatus(Request $request, Order $order)
    {
        $allowed = [
            'pending',
            'paid',
            'shipped',
            'cancelled',
        ];

        $request->validate([
            'status' => 'required|in:' . implode(',', $allowed),
        ]);

        $newStatus = $request->status;

        if ($order->status === $newStatus) {
            return back()->with(
                'status',
                'La orden ya tiene ese estado.'
            );
        }

        $order->update([
            'status' => $newStatus
        ]);

        return back()->with(
            'status',
            'Estado actualizado correctamente.'
        );
    }
}
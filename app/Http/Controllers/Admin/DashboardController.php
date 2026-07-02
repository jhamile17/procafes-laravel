<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Dashboard principal del administrador.
     */
    public function index(): View
    {
        $productos = Product::count();

        $clientes = User::whereHas('role', function ($query) {
            $query->where('codigo', 'CUSTOMER');
        })->count();

        $administradores = User::whereHas('role', function ($query) {
            $query->where('codigo', 'ADMIN');
        })->count();

        $pedidos = Order::count();

        $ventas = Order::sum('total_price');

        $productosStockBajo = Product::stockBajo()
            ->orderBy('stock')
            ->take(10)
            ->get();

        $ultimosProductos = Product::with(['category', 'brand'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [

            'productos' => $productos,

            'clientes' => $clientes,

            'administradores' => $administradores,

            'pedidos' => $pedidos,

            'ventas' => $ventas,

            'productosStockBajo' => $productosStockBajo,

            'ultimosProductos' => $ultimosProductos,

        ]);
    }
}
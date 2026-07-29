<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Cliente\PedidoService;
use Illuminate\Contracts\View\View;

class PedidoController extends Controller
{
    public function __construct(
        protected PedidoService $pedidoService
    ) {
    }

    /**
     * Mostrar los pedidos del cliente.
     */
    public function index(): View
    {
        $orders = $this->pedidoService
            ->getOrders(auth()->id());

        return view('customer.orders', compact('orders'));
    }
}
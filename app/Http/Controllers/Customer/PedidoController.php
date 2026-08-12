<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Services\Cliente\PedidoService;
use App\Models\Order;
use Illuminate\Contracts\View\View;
use App\Models\ConfiguracionEmpresa;
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
    public function show(int $order): View
    {
        $order = Order::query()
            ->with([
                'estadoPedido',
                'items.product',
                'payment',
                'comprobante.estadoComprobante',
                'comprobante.electronicDocument',
            ])
            ->where('user_id', auth()->id())
            ->findOrFail($order);
            $empresa = ConfiguracionEmpresa::first();
        return view(
            'customer.orders.show',
            compact('order','empresa')
        );
    }
}
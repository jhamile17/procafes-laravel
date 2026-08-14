<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionEmpresa;
use App\Models\Order;
use App\Services\Cliente\PedidoService;
use Illuminate\Contracts\View\View;

class PedidoController extends Controller
{
    public function __construct(
        protected PedidoService $pedidoService
    ) {
    }

    /**
     * Mostrar todos los pedidos del cliente.
     */
    public function index(): View
    {
        $orders = $this->pedidoService->getOrders(auth()->id());

        return view('customer.orders', compact('orders'));
    }

    /**
     * Mostrar el detalle de un pedido.
     */
    public function show(int $order): View
    {
        $order = Order::with([
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
            compact('order', 'empresa')
        );
    }
}
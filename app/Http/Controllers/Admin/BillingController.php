<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Mostrar pedidos disponibles para facturación.
     */
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->with([
                'user',
                'estadoPedido',
                'items.product',
                'payment.paymentMethod',
                'payment.estadoPago',
                'comprobante.estadoComprobante',
                'comprobante.electronicDocument',
            ])

            // Buscar por número de pedido
            ->when(
                $request->filled('numero_pedido'),
                function ($query) use ($request) {

                    $query->where(
                        'numero_pedido',
                        'like',
                        '%' . trim($request->numero_pedido) . '%'
                    );
                }
            )

            ->latest()
            ->paginate(8)
            ->withQueryString();

        return view(
            'admin.billing.index',
            compact('orders')
        );
    }

    /**
     * Buscar pedido.
     */
    public function lookup(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'numero_pedido' => [
                    'required',
                    'string',
                    'max:30',
                ],
            ],
            [
                'numero_pedido.required' =>
                    'Ingresa el número de pedido.',

                'numero_pedido.max' =>
                    'El número de pedido es demasiado largo.',
            ]
        );

        return redirect()->route(
            'admin.billing.index',
            [
                'numero_pedido' => trim(
                    $request->numero_pedido
                ),
            ]
        );
    }

    /**
     * Aprobar manualmente un pago realizado en tienda.
     */
    public function approvePayment(
        int $order
    ): RedirectResponse {

        $order = Order::query()
            ->with([
                'payment.paymentMethod',
                'payment.estadoPago',
            ])
            ->findOrFail($order);

        if (! $order->payment) {
            return back()->with(
                'error',
                'El pedido no tiene un pago registrado.'
            );
        }

        $payment = $order->payment;

        if (! $this->paymentService->esPagoEnTienda($payment)) {
            return back()->with(
                'error',
                'Este pago no corresponde al método Pago en tienda.'
            );
        }

        if (! $payment->isPendiente()) {
            return back()->with(
                'error',
                'El pago ya no se encuentra pendiente.'
            );
        }

        $this->paymentService->confirmarPago(
            payment: $payment
        );

        return back()->with(
            'success',
            'El pago del pedido '
            . $order->numero_pedido .
            ' fue aprobado correctamente.'
        );
    }
}
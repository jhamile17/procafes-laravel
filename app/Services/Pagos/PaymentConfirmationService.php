<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\Pagos\PaymentConfirmationService;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService,
        protected PaymentConfirmationService $paymentConfirmationService
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
            ->latest()
            ->limit(300)
            ->get();

        return view(
            'admin.billing.index',
            compact('orders')
        );
    }

    /**
     * Aprobar un pago realizado en tienda.
     *
     * Flujo:
     *
     * Pago pendiente
     *      ↓
     * Admin aprueba
     *      ↓
     * PaymentConfirmationService
     *      ↓
     * Pago aprobado
     *      ↓
     * Pedido confirmado
     *      ↓
     * NubeFact emite comprobante
     *      ↓
     * Se guarda el documento electrónico
     */
    public function approvePayment(
        int $order
    ): RedirectResponse {

        $order = Order::query()
            ->with([
                'payment.paymentMethod',
                'payment.estadoPago',
                'comprobante.electronicDocument',
            ])
            ->findOrFail($order);

        /*
        |--------------------------------------------------------------------------
        | Verificar que exista un pago
        |--------------------------------------------------------------------------
        */

        if (! $order->payment) {

            return back()->with(
                'error',
                'El pedido no tiene un pago registrado.'
            );
        }

        $payment = $order->payment;

        /*
        |--------------------------------------------------------------------------
        | Verificar que sea pago en tienda
        |--------------------------------------------------------------------------
        */

        if (! $this->paymentService->esPagoEnTienda($payment)) {

            return back()->with(
                'error',
                'Este pedido no corresponde a un pago en tienda.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Verificar que el pago esté pendiente
        |--------------------------------------------------------------------------
        */

        if (! $payment->isPendiente()) {

            return back()->with(
                'error',
                'El pago de este pedido ya fue procesado.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Confirmar pago + pedido + comprobante
        |--------------------------------------------------------------------------
        |
        | PaymentConfirmationService se encarga de todo el flujo:
        |
        | 1. Aprobar el pago.
        | 2. Confirmar el pedido.
        | 3. Emitir el comprobante mediante NubeFact.
        |
        */

        try {

            $payment = $this->paymentConfirmationService->confirmar(
                payment: $payment
            );

            /*
            |--------------------------------------------------------------------------
            | Obtener comprobante actualizado
            |--------------------------------------------------------------------------
            */

            $payment->load([
                'order.comprobante.electronicDocument',
            ]);

            $comprobante =
                $payment->order?->comprobante;

            $document =
                $comprobante?->electronicDocument;

            /*
            |--------------------------------------------------------------------------
            | Verificar resultado
            |--------------------------------------------------------------------------
            */

            if ($document) {

                return back()->with(
                    'success',
                    'El pago fue aprobado y el comprobante electrónico del pedido '
                    . $order->numero_pedido
                    . ' fue generado correctamente.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Pago aprobado pero comprobante no disponible
            |--------------------------------------------------------------------------
            */

            return back()->with(
                'warning',
                'El pago del pedido '
                . $order->numero_pedido
                . ' fue aprobado, pero el comprobante electrónico todavía no está disponible.'
            );

        } catch (\Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'No fue posible aprobar el pago o generar el comprobante electrónico del pedido '
                . $order->numero_pedido
                . '.'
            );
        }
    }
}
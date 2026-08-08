<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Services\Checkout\CheckoutService;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService,
        protected PaymentService $paymentService,
    ) {
    }

    /*Mostrar checkout*/

    public function index(): View
    {
        return view(
            'checkout.index',
            $this->checkoutService->obtenerResumen(
                auth()->id()
            )
        );
    }

    /*Procesar checkout*/

    public function store(
        CheckoutRequest $request
    ): RedirectResponse {

        try {
            /* crear pedido, comprobante y registro de pago */
            $order = $this->checkoutService->procesar(
                auth()->id(),
                $request->validated()
            );
            /*Iniciar pago*/
            
            $payment = $this->paymentService
                ->iniciarPagoPorPedido($order);
            
            /*Mercado Pago*/

            if (
                $this->paymentService->esMercadoPago($payment)) {
                $initPoint= $payment->transaction_data['init_point']?? null;
                    if (!$initPoint){
                        throw new \RuntimerException(
                            'No fue posible iniciar el pago con Mercado Pago'
                        );
                    }
                return redirect ()->away($initPoint);
            }

            /*Pago en tienda*/

            return redirect()->route(
                'checkout.success',
                [
                    'order'=>$order,
                ]
            );
        } catch (Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'No fue posible procesar tu pedido. Inténtalo nuevamente.'
                );

        }

    }
}
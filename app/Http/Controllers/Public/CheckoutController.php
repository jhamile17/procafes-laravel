<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\Checkout\CheckoutRequest;
use App\Services\Checkout\CheckoutService;
use App\Services\Pagos\PaymentService;
use App\Notifications\PedidoRealizadoNotification;
use Illuminate\Http\RedirectResponse;
use RuntimeException;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService,
        protected PaymentService $paymentService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar checkout
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        try {

            $data = $this->checkoutService->obtenerResumen(
                auth()->id()
            );

            return view('checkout.index', $data);

        } catch (RuntimeException $e) {

            return redirect()
                ->route('cart.index')
                ->with(
                    'warning',
                    'Tu carrito está vacío. Agrega productos para continuar con tu compra.'
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar checkout
    |--------------------------------------------------------------------------
    */

    public function store(
        CheckoutRequest $request
    ): RedirectResponse {

        try {

            $order = $this->checkoutService->procesar(
                auth()->id(),
                $request->validated()
            );

            $payment = $this->paymentService
                ->iniciarPagoPorPedido($order);

            if (! $this->paymentService->esMercadoPago($payment)) {

                $order->user->notify(
                    new PedidoRealizadoNotification($order)
                );
            }

            if ($this->paymentService->esMercadoPago($payment)) {

                $initPoint = $payment->transaction_data['init_point'] ?? null;

                if (blank($initPoint)) {
                    throw new RuntimeException(
                        'No fue posible iniciar el pago con Mercado Pago.'
                    );
                }

                return redirect()->away($initPoint);
            }

            return redirect()
                ->route('customer.orders')
                ->with(
                    'success',
                    '¡Pedido creado correctamente! Tu pedido fue registrado. Te enviamos los detalles a tu correo electrónico.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()->with(
                'error',
                'Ocurrió un error al procesar el pedido.'
            );
        }
    }
}
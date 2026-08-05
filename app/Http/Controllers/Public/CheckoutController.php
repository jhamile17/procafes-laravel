<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
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
    | Mostrar Checkout
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $data = $this->checkoutService->obtenerResumen(
            auth()->id()
        );
        return view(
            'checkout.index',
            $data
        );
        
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar Checkout
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {
        $data = $request->validate([

            'payment_method_id' => [
                'required',
                'integer',
                'exists:payment_methods,id',
            ],

            'alias' => [
                'nullable',
                'string',
                'max:100',
            ],

            'direccion' => [
                'required',
                'string',
                'max:255',
            ],

            'departamento' => [
                'required',
                'string',
                'max:100',
            ],

            'provincia' => [
                'required',
                'string',
                'max:100',
            ],

            'distrito' => [
                'required',
                'string',
                'max:100',
            ],

            'referencia' => [
                'nullable',
                'string',
                'max:255',
            ],

            'latitude' => [
                'required',
                'numeric',
            ],

            'longitude' => [
                'required',
                'numeric',
            ],

        ]);

        try {

            $order = $this->checkoutService->procesar(

                auth()->id(),

                $data

            );

            $payment = $this->paymentService
                ->obtenerPorPedido(
                    $order
                );

            $payment = $this->paymentService
                ->iniciarPago(
                    $payment
                );
            /*
            |--------------------------------------------------------------------------
            | Mercado Pago
            |--------------------------------------------------------------------------
            */

            if (
                $this->paymentService
                    ->esMercadoPago($payment)
            ) {

                return redirect()->away(
                    $payment->transaction_data['init_point']
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Pago en tienda
            |--------------------------------------------------------------------------
            */

            return redirect()

                ->route(
                    'checkout.success',
                    $order
                )

                ->with(
                    'success',
                    'Tu pedido fue registrado correctamente. Acércate a la tienda para completar el pago.'
                );

        } catch (Throwable $e) {

            report($e);

            return back()

                ->withInput()

                ->with(
                    'error',
                    $e->getMessage()
                );

        }

    }
}
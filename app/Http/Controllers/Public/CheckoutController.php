<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CheckoutController extends Controller
{
    public function __construct(
        protected CheckoutService $checkoutService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Mostrar Checkout
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): View|RedirectResponse
    {
        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Debe iniciar sesión para continuar con la compra.'
                );

        }

        $resumen = $this->checkoutService
            ->obtenerResumen(
                $request->user()->id
            );

        return view(
            'checkout.index',
            $resumen
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar Checkout
    |--------------------------------------------------------------------------
    */

    public function store(Request $request): RedirectResponse
    {
        if (! $request->user()) {

            return redirect()
                ->route('login')
                ->with(
                    'info',
                    'Debe iniciar sesión para continuar con la compra.'
                );

        }

        $data = $request->validate([

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'state' => [
                'required',
                'string',
                'max:100',
            ],

            'zip_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'delivery_type' => [
                'required',
                'string',
            ],

            'payment_method_id' => [
                'required',
                'integer',
            ],

            'reference' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);

        try {

            // Este bloque lo cambiaremos en el siguiente paso
            // cuando modifiquemos CheckoutService.

            $order = $this->checkoutService->procesar(

                $request->user()->id,

                [
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip_code' => $data['zip_code'] ?? null,
                    'country' => $data['country'],
                    'reference' => $data['reference'] ?? null,
                ],

                $data['delivery_type'],

                (int) $data['payment_method_id'],

                $data['observaciones'] ?? null,

            );

            if (empty($order->checkout_url)) {

                return back()->with(
                    'error',
                    'No fue posible generar el enlace de pago.'
                );

            }

            return redirect()->away(
                $order->checkout_url
            );

        } catch (\Throwable $e) {

            dd([
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

        }
    }
}
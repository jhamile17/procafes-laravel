<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Services\Checkout\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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
        $data = $request->validate([

            'shipping_address_id' => [
                'required',
                'integer',
            ],

            'delivery_type' => [
                'required',
                'string',
            ],

            'payment_method_id' => [
                'required',
                'integer',
            ],

            'observaciones' => [
                'nullable',
                'string',
                'max:1000',
            ],

        ]);

        $order = $this->checkoutService->procesar(

            $request->user()->id,

            (int) $data['shipping_address_id'],

            $data['delivery_type'],

            (int) $data['payment_method_id'],

            $data['observaciones'] ?? null,

        );

        return redirect()
            ->route('customer.dashboard')
            ->with(
                'success',
                "Pedido {$order->numero_pedido} creado correctamente."
            );
    }
}
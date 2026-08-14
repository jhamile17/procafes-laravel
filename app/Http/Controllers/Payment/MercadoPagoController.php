<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class MercadoPagoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Pago aprobado
    |--------------------------------------------------------------------------
    */

    public function success(): RedirectResponse
    {
        return $this->redirectTo(

            'success',
            '¡Pago realizado correctamente! Tu pedido fue registrado y hemos enviado el número de pedido a tu correo electrónico.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago pendiente
    |--------------------------------------------------------------------------
    */

    public function pending(): RedirectResponse
    {
        return $this->redirectToOrders(

            'warning',

            'Tu pago está pendiente de confirmación por Mercado Pago.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago rechazado
    |--------------------------------------------------------------------------
    */

    public function failure(): RedirectResponse
    {
        return $this->redirectToOrders(

            'error',

            'No fue posible completar el pago. Puedes intentarlo nuevamente.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Redirección
    |--------------------------------------------------------------------------
    */

    private function redirectToOrders(
    string $type,
    string $message
    ): RedirectResponse {

        return redirect()
            ->route('customer.orders')
            ->with($type, $message);

    }
}
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
        return $this->redirectToProfile(

            'success',

            'Tu pago fue recibido. Estamos confirmándolo con Mercado Pago.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago pendiente
    |--------------------------------------------------------------------------
    */

    public function pending(): RedirectResponse
    {
        return $this->redirectToProfile(

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
        return $this->redirectToProfile(

            'error',

            'No fue posible completar el pago. Puedes intentarlo nuevamente.'

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Redirección
    |--------------------------------------------------------------------------
    */

    private function redirectToProfile(
        string $type,
        string $message
    ): RedirectResponse {

        return redirect()

            ->route('customer.profile')

            ->with(

                $type,

                $message

            );

    }
}
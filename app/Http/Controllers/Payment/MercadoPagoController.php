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
        return redirect()
            ->route('customer.profile')
            ->with(
                'success',
                'Tu pago fue registrado correctamente. Estamos verificando la confirmación con Mercado Pago.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago pendiente
    |--------------------------------------------------------------------------
    */

    public function pending(): RedirectResponse
    {
        return redirect()
            ->route('customer.profile')
            ->with(
                'warning',
                'Tu pago se encuentra pendiente. Te notificaremos cuando Mercado Pago confirme la operación.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago rechazado
    |--------------------------------------------------------------------------
    */

    public function failure(): RedirectResponse
    {
        return redirect()
            ->route('customer.profile')
            ->with(
                'error',
                'El pago fue rechazado o cancelado. Puedes intentarlo nuevamente con el mismo pedido.'
            );
    }
}
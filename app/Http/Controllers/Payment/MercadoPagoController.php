<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Pago aprobado
    |--------------------------------------------------------------------------
    */

    public function success(Request $request): RedirectResponse
    {
        return redirect()
            ->route('customer.profile')
            ->with(
                'success',
                'Tu pago fue realizado correctamente. Estamos verificando la operación.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago pendiente
    |--------------------------------------------------------------------------
    */

    public function pending(Request $request): RedirectResponse
    {
        return redirect()
            ->route('customer.profile')
            ->with(
                'warning',
                'Tu pago quedó pendiente de confirmación.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Pago rechazado
    |--------------------------------------------------------------------------
    */

    public function failure(Request $request): RedirectResponse
    {
        return redirect()
            ->route('customer.profile')
            ->with(
                'error',
                'El pago fue rechazado o cancelado.'
            );
    }
}
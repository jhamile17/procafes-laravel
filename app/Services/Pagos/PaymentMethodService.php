<?php

namespace App\Services\Pagos;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodService
{
    /*
    |--------------------------------------------------------------------------
    | Obtener todos los métodos activos
    |--------------------------------------------------------------------------
    */

    public function obtenerActivos(): Collection
    {
        return PaymentMethod::query()

            ->activos()

            ->orderBy('nombre')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener un método de pago activo
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $paymentMethodId
    ): PaymentMethod {

        return PaymentMethod::query()

            ->activos()

            ->findOrFail($paymentMethodId);

    }
}
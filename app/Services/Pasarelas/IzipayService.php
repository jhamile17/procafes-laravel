<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Models\Payment;

class IzipayService
{
    /*
    |--------------------------------------------------------------------------
    | Crear transacción
    |--------------------------------------------------------------------------
    */

    public function crearTransaccion(Payment $payment): array
    {
        // TODO: Integrar API Izipay

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar transacción
    |--------------------------------------------------------------------------
    */

    public function consultarTransaccion(string $transactionId): array
    {
        // TODO

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Capturar pago
    |--------------------------------------------------------------------------
    */

    public function capturarPago(string $transactionId): array
    {
        // TODO

        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | Reembolsar
    |--------------------------------------------------------------------------
    */

    public function reembolsar(string $transactionId): array
    {
        // TODO

        return [];
    }
}
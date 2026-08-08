<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use RuntimeException;
use Throwable;

final class MercadoPagoApiService
{
    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');

        if (blank($accessToken)) {
            throw new RuntimeException(
                'No se ha configurado el Access Token de Mercado Pago.'
            );
        }

        MercadoPagoConfig::setAccessToken($accessToken);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener pago desde Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function obtenerPago(
        int|string $paymentId
    ): object {

        try {

            return $this->client()->get(
                (int) $paymentId
            );

        } catch (Throwable $e) {

            throw new RuntimeException(
                'No fue posible obtener el pago desde Mercado Pago.',
                previous: $e
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Cliente SDK
    |--------------------------------------------------------------------------
    */

    private function client(): PaymentClient
    {
        return new PaymentClient();
    }
}
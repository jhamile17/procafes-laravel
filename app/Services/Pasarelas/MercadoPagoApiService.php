<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use Illuminate\Support\Facades\Log;
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

            Log::error('ERROR SDK MERCADO PAGO', [
                'payment_id' => $paymentId,
                'message'    => $e->getMessage(),
                'code'       => $e->getCode(),
                'class'      => get_class($e),
                'file'       => $e->getFile(),
                'line'       => $e->getLine(),
                'trace'      => $e->getTraceAsString(),
            ]);

            throw $e;
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
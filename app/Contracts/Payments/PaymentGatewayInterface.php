<?php

namespace App\Contracts\Payments;

use App\Models\Order;
use App\Models\Payment;

interface PaymentGatewayInterface
{
    /*
    |--------------------------------------------------------------------------
    | Crear preferencia de pago
    |--------------------------------------------------------------------------
    */

    public function crearPago(
        Order $order,
        Payment $payment
    ): array;

    /*
    |--------------------------------------------------------------------------
    | Consultar estado del pago
    |--------------------------------------------------------------------------
    */

    public function consultarPago(
        string $transactionId
    ): array;

    /*
    |--------------------------------------------------------------------------
    | Procesar webhook
    |--------------------------------------------------------------------------
    */

    public function procesarWebhook(
        array $payload
    ): array;

    /*
    |--------------------------------------------------------------------------
    | Cancelar pago
    |--------------------------------------------------------------------------
    */

    public function cancelarPago(
        Payment $payment
    ): bool;

    /*
    |--------------------------------------------------------------------------
    | Reembolsar pago
    |--------------------------------------------------------------------------
    */

    public function reembolsarPago(
        Payment $payment,
        ?float $monto = null
    ): bool;
}
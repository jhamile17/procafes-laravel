<?php

declare(strict_types=1);

namespace App\Services\Pagos;

use App\Models\Payment;
use App\Services\Facturacion\NubeFactService;
use App\Services\Ventas\OrderService;
use Illuminate\Support\Facades\DB;

final class PaymentConfirmationService
{
    public function __construct(
        protected PaymentService $paymentService,
        protected OrderService $orderService,
        protected NubeFactService $nubeFactService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Confirmar proceso de pago
    |--------------------------------------------------------------------------
    */

    public function confirmar(
        Payment $payment,
        ?string $transactionId = null,
        array $transactionData = []
    ): Payment {

        return DB::transaction(function () use (
            $payment,
            $transactionId,
            $transactionData
        ) {

            /*
            |--------------------------------------------------------------------------
            | Cargar relaciones necesarias
            |--------------------------------------------------------------------------
            */

            $payment->loadMissing([
                'order.comprobante.electronicDocument',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Confirmar pago
            |--------------------------------------------------------------------------
            */

            $payment = $this->paymentService->confirmarPago(
                payment: $payment,
                transactionId: $transactionId,
                transactionData: $transactionData,
            );

            /*
            |--------------------------------------------------------------------------
            | Confirmar pedido
            |--------------------------------------------------------------------------
            */

            $this->orderService->confirmarPedido(
                $payment->order
            );

            /*
            |--------------------------------------------------------------------------
            | Emitir comprobante (solo una vez)
            |--------------------------------------------------------------------------
            */

            if (! $payment->order->comprobante->yaFueEmitido()) {

                $this->nubeFactService->emitir(
                    $payment->order->comprobante
                );

            }

            /*
            |--------------------------------------------------------------------------
            | Retornar información actualizada
            |--------------------------------------------------------------------------
            */

            return $payment->fresh([
                'order.comprobante.electronicDocument',
                'paymentMethod',
                'estadoPago',
            ]);

        });

    }
}
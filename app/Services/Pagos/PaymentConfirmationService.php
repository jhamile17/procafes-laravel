<?php

declare(strict_types=1);

namespace App\Services\Pagos;

use App\Models\Payment;
use App\Services\Ventas\CartService;
use App\Services\Facturacion\NubeFactService;
use App\Notifications\PagoConfirmadoNotification;
use Illuminate\Support\Facades\DB;

final class PaymentConfirmationService
{
    public function __construct(
        protected PaymentService $paymentService,
        protected CartService $cartService,
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
                'order.user',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Confirmar SOLAMENTE el pago
            |--------------------------------------------------------------------------
            */

            $payment = $this->paymentService->confirmarPago(
                payment: $payment,
                transactionId: $transactionId,
                transactionData: $transactionData,
            );

            /*
            |--------------------------------------------------------------------------
            | Emitir comprobante
            |--------------------------------------------------------------------------
            */

            $comprobante = $payment->order->comprobante;

            if (
                $comprobante &&
                ! $comprobante->yaFueEmitido()
            ) {
                $this->nubeFactService->emitir($comprobante);
            }

            /*
            |--------------------------------------------------------------------------
            | Vaciar carrito
            |--------------------------------------------------------------------------
            */

            $this->cartService->vaciarPorUsuario(
                $payment->order->user_id
            );

            /*
            |--------------------------------------------------------------------------
            | Notificar pago confirmado
            |--------------------------------------------------------------------------
            */

            DB::afterCommit(function () use ($payment) {

                $payment->order->user?->notify(
                    new PagoConfirmadoNotification(
                        $payment->order
                    )
                );

            });

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
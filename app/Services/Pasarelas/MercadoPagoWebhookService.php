<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Models\Payment;
use App\Services\Pagos\PaymentConfirmationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

final class MercadoPagoWebhookService
{
    public function __construct(
        protected MercadoPagoApiService $mercadoPagoApiService,
        protected MercadoPagoSignatureService $signatureService,
        protected PaymentConfirmationService $paymentConfirmationService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Procesar Webhook
    |--------------------------------------------------------------------------
    */

    public function procesar(
        Request $request
    ): JsonResponse {

        Log::info(
            'Webhook Mercado Pago recibido.',
            $request->all()
        );

        /*
        |--------------------------------------------------------------------------
        | Validar firma del Webhook
        |--------------------------------------------------------------------------
        */

        if (! $this->signatureService->validar($request)) {

            return response()->json([
                'message' => 'Firma inválida.',
            ], 401);

        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Solo procesar eventos de pago
            |--------------------------------------------------------------------------
            */

            if ($request->input('type') !== 'payment') {

                return response()->json([
                    'message' => 'Evento ignorado.',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Obtener ID del pago
            |--------------------------------------------------------------------------
            */

            $paymentId = $request->input('data.id');

            if (blank($paymentId)) {

                return response()->json([
                    'message' => 'Payment ID no recibido.',
                ], 400);

            }

            /*
            |--------------------------------------------------------------------------
            | Consultar pago en Mercado Pago
            |--------------------------------------------------------------------------
            */

            $mercadoPagoPayment = $this->mercadoPagoApiService
                ->obtenerPago($paymentId);

            /*
            |--------------------------------------------------------------------------
            | Solo procesar pagos aprobados
            |--------------------------------------------------------------------------
            */

            if ($mercadoPagoPayment->status !== 'approved') {

                return response()->json([
                    'message' => 'Pago no aprobado.',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Buscar pago en nuestra base de datos
            |--------------------------------------------------------------------------
            */

            $payment = Payment::query()
                ->where(
                    'reference',
                    $mercadoPagoPayment->external_reference
                )
                ->firstOrFail();

            /*
            |--------------------------------------------------------------------------
            | Evitar procesar dos veces
            |--------------------------------------------------------------------------
            */

            $payment->loadMissing('estadoPago');

            if ($payment->estadoPago?->esAprobado()) {

                return response()->json([
                    'message' => 'Pago ya procesado.',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Confirmar pago
            |--------------------------------------------------------------------------
            */

            $this->paymentConfirmationService->confirmar(

                payment: $payment,

                transactionId: (string) $mercadoPagoPayment->id,

                transactionData: json_decode(
                    json_encode($mercadoPagoPayment),
                    true
                ),

            );

            return response()->json([
                'message' => 'OK',
            ]);

        } catch (ModelNotFoundException $e) {

            Log::warning(
                'Pago no encontrado.',
                [
                    'payment_id' => $request->input('data.id'),
                ]
            );

            return response()->json([
                'message' => 'Pago no encontrado.',
            ], 404);

        } catch (Throwable $e) {

            Log::error(
                'Error procesando Webhook de Mercado Pago.',
                [
                    'message' => $e->getMessage(),
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ]
            );

            return response()->json([
                'message' => 'Error interno.',
            ], 500);

        }

    }
}
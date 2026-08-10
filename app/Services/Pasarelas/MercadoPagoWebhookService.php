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

    public function procesar(Request $request): JsonResponse
    {
        Log::info(
            'Webhook Mercado Pago recibido.',
            [
                'headers' => $request->headers->all(),
                'body'    => $request->all(),
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Validar firma
        |--------------------------------------------------------------------------
        */

        if (! $this->signatureService->validar($request)) {

            Log::warning('Webhook con firma inválida.');

            return response()->json([
                'message' => 'Firma inválida.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Solo eventos payment
        |--------------------------------------------------------------------------
        */

        if ($request->input('type') !== 'payment') {

            Log::info('Evento ignorado.', [
                'type' => $request->input('type'),
            ]);

            return response()->json([
                'message' => 'Evento ignorado.',
            ], 200);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment ID
        |--------------------------------------------------------------------------
        */

        $paymentId = $request->input('data.id');

        if (blank($paymentId)) {

            Log::warning('Webhook sin payment_id.');

            return response()->json([
                'message' => 'Payment ID no recibido.',
            ], 200);
        }

        try {

            /*
            |--------------------------------------------------------------------------
            | Consultar Mercado Pago
            |--------------------------------------------------------------------------
            */

            try {

                $mercadoPagoPayment = $this->mercadoPagoApiService
                    ->obtenerPago($paymentId);

            } catch (Throwable $e) {

                Log::warning(
                    'No fue posible obtener el pago desde Mercado Pago.',
                    [
                        'payment_id' => $paymentId,
                        'error'      => $e->getMessage(),
                    ]
                );

                return response()->json([
                    'message' => 'OK',
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Solo pagos aprobados
            |--------------------------------------------------------------------------
            */

            if ($mercadoPagoPayment->status !== 'approved') {

                Log::info('Pago aún no aprobado.', [
                    'payment_id' => $paymentId,
                    'status'     => $mercadoPagoPayment->status,
                ]);

                return response()->json([
                    'message' => 'Pago no aprobado.',
                ], 200);
            }

            /*
            |--------------------------------------------------------------------------
            | Buscar pago local
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
            | Evitar doble procesamiento
            |--------------------------------------------------------------------------
            */

            $payment->loadMissing('estadoPago');

            if ($payment->estadoPago?->esAprobado()) {

                Log::info('Pago ya procesado.', [
                    'payment_id' => $paymentId,
                ]);

                return response()->json([
                    'message' => 'Pago ya procesado.',
                ], 200);
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

            Log::info('Pago confirmado correctamente.', [
                'payment_id' => $paymentId,
            ]);

            return response()->json([
                'message' => 'OK',
            ], 200);

        } catch (ModelNotFoundException $e) {

            Log::warning(
                'Pago no encontrado en la base de datos.',
                [
                    'payment_id' => $paymentId,
                    'reference'  => $mercadoPagoPayment->external_reference ?? null,
                ]
            );

            return response()->json([
                'message' => 'Pago no encontrado.',
            ], 200);

        } catch (Throwable $e) {

            Log::error(
                'Error procesando Webhook de Mercado Pago.',
                [
                    'payment_id' => $paymentId,
                    'message'    => $e->getMessage(),
                    'file'       => $e->getFile(),
                    'line'       => $e->getLine(),
                    'trace'      => $e->getTraceAsString(),
                ]
            );

            return response()->json([
                'message' => 'OK',
            ], 200);
        }
    }
}
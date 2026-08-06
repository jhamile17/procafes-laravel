<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Services\Pagos\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use RuntimeException;
use Throwable;

class MercadoPagoWebhookService
{
    public function __construct(
        protected PaymentService $paymentService,
    ) {

        $accessToken = config(
            'mercadopago.access_token'
        );

        if (blank($accessToken)) {

            throw new RuntimeException(

                'No se configuró el Access Token de Mercado Pago.'

            );

        }

        MercadoPagoConfig::setAccessToken(
            $accessToken
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Procesar webhook
    |--------------------------------------------------------------------------
    */

    public function procesar(
        Request $request
    ): JsonResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | Solo pagos
            |--------------------------------------------------------------------------
            */

            if (

                $request->input('type') !== 'payment'

            ) {

                return response()->json([

                    'success' => true,

                    'message' => 'Evento ignorado.',

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Obtener ID del pago
            |--------------------------------------------------------------------------
            */

            $paymentId = $request->input(
                'data.id'
            );

            if (blank($paymentId)) {

                return response()->json([

                    'success' => false,

                    'message' => 'No se recibió el ID del pago.',

                ], 400);

            }

            /*
            |--------------------------------------------------------------------------
            | Obtener pago desde Mercado Pago
            |--------------------------------------------------------------------------
            */

            $mpPayment = $this->obtenerPagoMercadoPago(
                (string) $paymentId
            );

            /*
            |--------------------------------------------------------------------------
            | Buscar pago interno
            |--------------------------------------------------------------------------
            */

            $payment = $this->paymentService
                ->obtenerPorReferencia(

                    $mpPayment->external_reference

                );

            if (! $payment) {

                return response()->json([

                    'success' => false,

                    'message' => 'Pago no encontrado.',

                ], 404);

            }

            /*
            |--------------------------------------------------------------------------
            | Evitar reprocesar
            |--------------------------------------------------------------------------
            */

            $payment->loadMissing(
                'estadoPago'
            );

            if (

                $payment->estadoPago
                    ->esAprobado()

            ) {

                return response()->json([

                    'success' => true,

                    'message' => 'Pago ya procesado.',

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Procesar estado
            |--------------------------------------------------------------------------
            */

            return $this->procesarEstado(

                $payment,

                $mpPayment

            );

        } catch (Throwable $e) {

            report($e);

            return response()->json([

                'success' => false,

                'message' => $e->getMessage(),

            ], 500);

        }

    }
        /*
    |--------------------------------------------------------------------------
    | Obtener pago desde Mercado Pago
    |--------------------------------------------------------------------------
    */

    private function obtenerPagoMercadoPago(
        string $paymentId
    ): object {

        return $this->paymentClient()
            ->get(
                $paymentId
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Construir información de la transacción
    |--------------------------------------------------------------------------
    */

    private function construirTransactionData(
        object $mpPayment
    ): array {

        return [

            'id' => $mpPayment->id,

            'status' => $mpPayment->status,

            'status_detail' => $mpPayment->status_detail,

            'payment_method' => $mpPayment->payment_method_id,

            'payment_type' => $mpPayment->payment_type_id,

            'external_reference' => $mpPayment->external_reference,

            'transaction_amount' => $mpPayment->transaction_amount,

            'currency_id' => $mpPayment->currency_id,

            'date_created' => $mpPayment->date_created,

            'date_approved' => $mpPayment->date_approved,

            'date_last_updated' => $mpPayment->date_last_updated,

            'collector_id' => $mpPayment->collector_id,

        ];

    }

    /*
    |--------------------------------------------------------------------------
    | Cliente Mercado Pago
    |--------------------------------------------------------------------------
    */

    private function paymentClient(): PaymentClient
    {

        return new PaymentClient();

    }
        /*
    |--------------------------------------------------------------------------
    | Procesar estado del pago
    |--------------------------------------------------------------------------
    */

    private function procesarEstado(
        \App\Models\Payment $payment,
        object $mpPayment
    ): JsonResponse {

        $transactionData = $this->construirTransactionData(
            $mpPayment
        );

        switch ($mpPayment->status) {

            /*
            |--------------------------------------------------------------------------
            | Pago aprobado
            |--------------------------------------------------------------------------
            */

            case 'approved':

                $this->paymentService
                    ->confirmarPago(

                        payment: $payment,

                        transactionId: (string) $mpPayment->id,

                        transactionData: $transactionData,

                    );

                break;

            /*
            |--------------------------------------------------------------------------
            | Pago rechazado
            |--------------------------------------------------------------------------
            */

            case 'rejected':

            case 'cancelled':

                $this->paymentService
                    ->rechazarPago(

                        payment: $payment,

                        transactionId: (string) $mpPayment->id,

                        transactionData: $transactionData,

                    );

                break;

            /*
            |--------------------------------------------------------------------------
            | Pago pendiente
            |--------------------------------------------------------------------------
            */

            case 'pending':

            case 'in_process':

            case 'authorized':

                $this->paymentService
                    ->actualizarTransaccion(

                        payment: $payment,

                        transactionId: (string) $mpPayment->id,

                        transactionData: $transactionData,

                    );

                break;

            /*
            |--------------------------------------------------------------------------
            | Otros estados
            |--------------------------------------------------------------------------
            */

            default:

                $this->paymentService
                    ->actualizarTransaccion(

                        payment: $payment,

                        transactionId: (string) $mpPayment->id,

                        transactionData: $transactionData,

                    );

                break;

        }

        return response()->json([

            'success' => true,

            'status' => $mpPayment->status,

        ]);

    }

}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Pagos\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Throwable;

class MercadoPagoWebhookController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
        MercadoPagoConfig::setAccessToken(
            config('mercadopago.access_token')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Webhook Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function handle(
        Request $request
    ): JsonResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | Solo procesar eventos de pago
            |--------------------------------------------------------------------------
            */

            if ($request->input('type') !== 'payment') {

                return response()->json([
                    'success' => true,
                    'message' => 'Evento ignorado.',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | ID del pago
            |--------------------------------------------------------------------------
            */

            $paymentId = $request->input('data.id');

            if (blank($paymentId)) {

                return response()->json([
                    'success' => false,
                    'message' => 'No se recibió el ID del pago.',
                ], 400);

            }

            /*
            |--------------------------------------------------------------------------
            | Consultar Mercado Pago
            |--------------------------------------------------------------------------
            */

            $client = new PaymentClient();

            $mpPayment = $client->get(
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
            | Datos de la transacción
            |--------------------------------------------------------------------------
            */

            $transactionData = [

                'status' => $mpPayment->status,

                'status_detail' => $mpPayment->status_detail,

                'payment_method' => $mpPayment->payment_method_id,

                'payment_type' => $mpPayment->payment_type_id,

                'external_reference' => $mpPayment->external_reference,

            ];

            /*
            |--------------------------------------------------------------------------
            | Procesar estado
            |--------------------------------------------------------------------------
            */

            switch ($mpPayment->status) {

                case 'approved':

                    $this->paymentService
                        ->confirmarPago(

                            payment: $payment,

                            transactionId: (string) $mpPayment->id,

                            transactionData: $transactionData

                        );

                    break;

                case 'rejected':

                case 'cancelled':

                    $this->paymentService
                        ->rechazarPago(

                            payment: $payment,

                            transactionId: (string) $mpPayment->id,

                            transactionData: $transactionData

                        );

                    break;

                case 'pending':

                case 'in_process':

                case 'authorized':

                    // El pago continúa en proceso.

                    break;

            }

            return response()->json([
                'success' => true,
            ]);

        } catch (Throwable $e) {

            report($e);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);

        }

    }
}
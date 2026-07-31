<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

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

    /**
     * Procesa las notificaciones enviadas por Mercado Pago.
     */
    public function handle(Request $request): JsonResponse
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Solo procesamos eventos de tipo payment
            |--------------------------------------------------------------------------
            */

            if ($request->input('type') !== 'payment') {

                return response()->json([
                    'success' => true,
                    'message' => 'Evento ignorado',
                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Obtener ID del pago
            |--------------------------------------------------------------------------
            */

            $paymentId = $request->input('data.id');

            if (empty($paymentId)) {

                return response()->json([
                    'success' => false,
                    'message' => 'Payment ID no recibido.',
                ], 400);

            }

            /*
            |--------------------------------------------------------------------------
            | Consultar el pago en Mercado Pago
            |--------------------------------------------------------------------------
            */

            $client = new PaymentClient();

            $mpPayment = $client->get($paymentId);

            /*
            |--------------------------------------------------------------------------
            | Buscar el pago en nuestra base de datos
            |--------------------------------------------------------------------------
            */

            $payment = $this->paymentService->obtenerPorReferencia(
                $mpPayment->external_reference
            );

            if (!$payment) {

                return response()->json([
                    'success' => false,
                    'message' => 'Pago no encontrado.',
                ], 404);

            }

            /*
            |--------------------------------------------------------------------------
            | Información de la transacción
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
            | Procesar según el estado recibido
            |--------------------------------------------------------------------------
            */

            switch ($mpPayment->status) {

                case 'approved':

                    $this->paymentService->confirmarPago(

                        payment: $payment,

                        transactionId: (string) $mpPayment->id,

                        transactionData: $transactionData

                    );

                    break;

                case 'rejected':

                    $this->paymentService->rechazarPago(

                        payment: $payment,

                        transactionData: $transactionData

                    );

                    break;

                case 'cancelled':

                    $this->paymentService->cancelarPago(

                        payment: $payment,

                        transactionData: $transactionData

                    );

                    break;

                case 'pending':

                case 'in_process':

                case 'authorized':

                default:

                    // No se realiza ninguna acción.
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
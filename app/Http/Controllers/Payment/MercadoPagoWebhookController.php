<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('[MP WEBHOOK]', $request->all());

        $type = $request->input('type');
        $paymentId = data_get($request->all(), 'data.id');

        if ($type !== 'payment' || !$paymentId) {
            return response()->json(['ok' => true]);
        }

        try {

            $response = Http::withToken(env('MP_ACCESS_TOKEN'))
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if (!$response->successful()) {

                Log::error('[MP WEBHOOK] Error consultando pago', [
                    'payment_id' => $paymentId
                ]);

                return response()->json(['ok' => false]);
            }

            $paymentData = $response->json();

            Log::info('[MP PAYMENT DETAIL]', $paymentData);

            /*
             |----------------------------------------------------
             | Buscar Order ID
             |----------------------------------------------------
             */

            $externalReference =
                $paymentData['external_reference'] ??
                null;

            if (!$externalReference) {

                Log::warning(
                    '[MP WEBHOOK] No existe external_reference'
                );

                return response()->json(['ok' => true]);
            }

            $order = Order::find($externalReference);

            if (!$order) {

                Log::warning(
                    '[MP WEBHOOK] Orden no encontrada',
                    ['order_id' => $externalReference]
                );

                return response()->json(['ok' => true]);
            }

            /*
             |----------------------------------------------------
             | Estado Mercado Pago
             |----------------------------------------------------
             */

            $mpStatus = $paymentData['status'] ?? 'pending';

            switch ($mpStatus) {

                case 'approved':
                    $order->status = 'paid';
                    break;

                case 'rejected':
                case 'cancelled':
                    $order->status = 'cancelled';
                    break;

                default:
                    $order->status = 'pending';
                    break;
            }

            $order->save();

            /*
             |----------------------------------------------------
             | Registrar pago
             |----------------------------------------------------
             */

            Payment::updateOrCreate(
                [
                    'transaction_id' => $paymentId
                ],
                [
                    'order_id' => $order->id,
                    'payment_method' => 'mercadopago',
                    'amount' => $paymentData['transaction_amount'] ?? 0,
                    'transaction_json' => json_encode($paymentData),
                    'status' => $mpStatus === 'approved'
                        ? 'completed'
                        : 'pending',
                ]
            );

            Log::info(
                '[MP WEBHOOK] Orden actualizada',
                [
                    'order_id' => $order->id,
                    'status' => $order->status
                ]
            );

        } catch (\Throwable $e) {

            Log::error(
                '[MP WEBHOOK ERROR]',
                [
                    'message' => $e->getMessage()
                ]
            );
        }

        return response()->json(['ok' => true]);
    }
}
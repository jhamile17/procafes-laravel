<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MercadoPagoWebhookController extends Controller
{
    public function handle(Request $request): JsonResponse
    {
        $type = $request->input('type');
        $paymentId = data_get($request->all(), 'data.id');

        Log::info('Mercado Pago webhook recibido.', [
            'type' => $type,
            'payment_id' => $paymentId,
        ]);

        if ($type !== 'payment' || blank($paymentId)) {
            return response()->json(['ok' => true]);
        }

        $accessToken = config('services.mercadopago.token');

        if (blank($accessToken)) {
            Log::error('Mercado Pago webhook sin token configurado.');

            return response()->json(['ok' => false], 500);
        }

        try {
            $response = Http::withToken($accessToken)
                ->acceptJson()
                ->get("https://api.mercadopago.com/v1/payments/{$paymentId}");

            if ($response->failed()) {
                Log::error('No se pudo consultar el pago en Mercado Pago.', [
                    'payment_id' => $paymentId,
                    'response' => $response->body(),
                ]);

                return response()->json(['ok' => false], 500);
            }

            $paymentData = $response->json();
            $orderId = $paymentData['external_reference'] ?? null;

            if (! ctype_digit((string) $orderId)) {
                Log::warning('Pago sin external_reference válido.', [
                    'payment_id' => $paymentId,
                    'external_reference' => $orderId,
                ]);

                return response()->json(['ok' => true]);
            }

            DB::transaction(function () use ($orderId, $paymentId, $paymentData) {
                $order = Order::query()
                    ->with(['items.product', 'payment'])
                    ->lockForUpdate()
                    ->find($orderId);

                if (! $order || ! $order->payment) {
                    Log::warning('Orden o pago local no encontrado.', [
                        'order_id' => $orderId,
                        'payment_id' => $paymentId,
                    ]);

                    return;
                }

                if ($order->payment->payment_method !== 'mercadopago') {
                    Log::warning('El método de pago local no es Mercado Pago.', [
                        'order_id' => $order->id,
                    ]);

                    return;
                }

                $mpStatus = (string) ($paymentData['status'] ?? '');
                $receivedAmount = round((float) ($paymentData['transaction_amount'] ?? 0), 2);
                $expectedAmount = round((float) $order->total_price, 2);

                $paymentStatus = match ($mpStatus) {
                    'approved' => Payment::STATUS_COMPLETED,
                    'rejected', 'cancelled' => Payment::STATUS_FAILED,
                    default => Payment::STATUS_PENDING,
                };

                $orderStatus = match ($paymentStatus) {
                    Payment::STATUS_COMPLETED => Order::STATUS_PAID,
                    Payment::STATUS_FAILED => Order::STATUS_CANCELLED,
                    default => Order::STATUS_PENDING,
                };

                if (
                    $paymentStatus === Payment::STATUS_COMPLETED
                    && abs($receivedAmount - $expectedAmount) > 0.01
                ) {
                    Log::error('Monto de Mercado Pago no coincide con la orden.', [
                        'order_id' => $order->id,
                        'expected_amount' => $expectedAmount,
                        'received_amount' => $receivedAmount,
                        'payment_id' => $paymentId,
                    ]);

                    $paymentStatus = Payment::STATUS_FAILED;
                    $orderStatus = Order::STATUS_CANCELLED;
                }

                $payment = $order->payment;

                // Un pago ya confirmado no debe retroceder por un webhook repetido.
                if ($payment->isCompleted()) {
                    return;
                }

                $payment->update([
                    'transaction_id' => (string) $paymentId,
                    'transaction_json' => $paymentData,
                    'amount' => $receivedAmount > 0 ? $receivedAmount : $expectedAmount,
                    'status' => $paymentStatus,
                ]);

                $order->update([
                    'status' => $orderStatus,
                ]);

                // El descuento de stock se hará aquí, una sola vez, cuando validemos
                // que Product y el flujo de inventario estén listos.
            });
        } catch (\Throwable $exception) {
            Log::error('Error procesando webhook de Mercado Pago.', [
                'payment_id' => $paymentId,
                'message' => $exception->getMessage(),
            ]);

            return response()->json(['ok' => false], 500);
        }

        return response()->json(['ok' => true]);
    }
}
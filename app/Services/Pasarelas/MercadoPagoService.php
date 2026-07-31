<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Resources\Preference;

class MercadoPagoService
{
    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');

        if (empty($accessToken)) {
            throw new Exception(
                'No se ha configurado el Access Token de Mercado Pago.'
            );
        }

        MercadoPagoConfig::setAccessToken($accessToken);
    }

    /**
     * Crear preferencia de pago.
     */
    public function crearPreferencia(Payment $payment): Preference
    {
        $payment->load([
            'order.user',
            'order.items.product',
        ]);

        $items = [];

        foreach ($payment->order->items as $item) {

            $items[] = [
                'id' => (string) $item->product->id,
                'title' => $item->product->name,
                'description' => $item->product->description ?? '',
                'quantity' => (int) $item->quantity,
                'currency_id' => 'PEN',
                'unit_price' => (float) $item->unit_price,
            ];
        }

        $client = new PreferenceClient();

        $request = [

            'items' => $items,

            'payer' => [
                'name' => $payment->order->user->name,
                'email' => $payment->order->user->email,
            ],

            'external_reference' => $payment->reference,

            'statement_descriptor' => 'PROCAFES',

            'binary_mode' => false,

            'metadata' => [
                'payment_id' => $payment->id,
                'order_id' => $payment->order->id,
            ],

            'back_urls' => [
                'success' => route('mp.success'),
                'failure' => route('mp.failure'),
                'pending' => route('mp.pending'),
            ],
            'notification_url' => 'https://pro-cafes.com/webhooks/mercadopago'
            // Vamos a quitar temporalmente esta línea
            // 'auto_return' => 'approved',
        ];

        try {

            $preference = $client->create($request);

            $payment->update([
                'transaction_data' => [
                    'preference_id'      => $preference->id,
                    'init_point'         => $preference->init_point,
                    'sandbox_init_point' => $preference->sandbox_init_point,
                ],
            ]);

            return $preference;

        } catch (MPApiException $e) {

            $response = $e->getApiResponse();

            Log::error('Mercado Pago', [
                'status' => $response->getStatusCode(),
                'content' => $response->getContent(),
                'request' => $request,
            ]);

            dd($response->getContent());

        } catch (\Throwable $e) {

            Log::error('Mercado Pago Throwable', [
                'mensaje' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
            ]);

            throw $e;
        }
    }
}
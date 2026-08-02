<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use RuntimeException;

class MercadoPagoService
{
    private const CURRENCY = 'PEN';

    private const STATEMENT_DESCRIPTOR = 'PROCAFES';

    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');

        if (blank($accessToken)) {

            throw new Exception(
                'No se ha configurado el Access Token de Mercado Pago.'
            );

        }

        MercadoPagoConfig::setAccessToken(
            $accessToken
        );
    }
    /*
|--------------------------------------------------------------------------
| Crear preferencia
|--------------------------------------------------------------------------
*/

public function crearPreferencia(
    Payment $payment
): array {

    $payment->load([

        'order.user',

        'order.items.product',

    ]);

    $request = $this->construirRequest(
        $payment
    );

    try {

        $preference = $this->preferenceClient()
            ->create($request);

        return [

            'preference_id' => $preference->id,

            'init_point' => $preference->init_point,

            'sandbox_init_point' => $preference->sandbox_init_point,

        ];

    } catch (MPApiException $e) {

        Log::error(
            'Mercado Pago API',
            [

                'status' => $e->getApiResponse()->getStatusCode(),

                'content' => $e->getApiResponse()->getContent(),

                'request' => $request,

            ]
        );

        throw new RuntimeException(
            'No fue posible crear la preferencia de Mercado Pago.'
        );

    } catch (\Throwable $e) {

        Log::error(
            'Mercado Pago',
            [

                'message' => $e->getMessage(),

                'file' => $e->getFile(),

                'line' => $e->getLine(),

            ]
        );

        throw $e;

    }

}
/*
|--------------------------------------------------------------------------
| Construir Request
|--------------------------------------------------------------------------
*/

private function construirRequest(
    Payment $payment
): array {

    return [

        'items' => $this->construirItems(
            $payment
        ),

        'payer' => $this->construirPayer(
            $payment
        ),

        'external_reference' => $payment->reference,

        'statement_descriptor' => self::STATEMENT_DESCRIPTOR,

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

        'notification_url' => route('mp.webhook'),

        'auto_return' => 'approved',

    ];

}
/*
|--------------------------------------------------------------------------
| Construir Items
|--------------------------------------------------------------------------
*/

private function construirItems(
    Payment $payment
): array {

    return $payment->order->items

        ->map(function ($item) {

            return [

                'id' => (string) $item->product->id,

                'title' => $item->product->name,

                'description' => $item->product->description ?? '',

                'quantity' => (int) $item->quantity,

                'currency_id' => self::CURRENCY,

                'unit_price' => (float) $item->unit_price,

            ];

        })

        ->values()

        ->toArray();

}
/*
|--------------------------------------------------------------------------
| Construir Pagador
|--------------------------------------------------------------------------
*/

private function construirPayer(
    Payment $payment
): array {

    return [

        'name' => $payment->order->user->name,

        'email' => $payment->order->user->email,

    ];

}
/*
|--------------------------------------------------------------------------
| Cliente Mercado Pago
|--------------------------------------------------------------------------
*/

private function preferenceClient(): PreferenceClient
{

    return new PreferenceClient();

}
}
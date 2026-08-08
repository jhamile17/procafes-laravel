<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;
use RuntimeException;

class MercadoPagoService
{
    private const CURRENCY = 'PEN';

    private const STATEMENT_DESCRIPTOR = 'PROCAFES';
    private const CATEGORY = 'food';

    public function __construct()
    {
        $accessToken = config('mercadopago.access_token');

        if (blank($accessToken)) {

            throw new RuntimeException(
                'No se ha configurado el Access Token de Mercado Pago.'
            );

        }

        MercadoPagoConfig::setAccessToken(
            $this->accessToken()
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

    $payment->loadMissing([

        'order.user',

        'order.items.product',

    ]);

    $request = $this->construirRequest(
        $payment
    );

    try {

        $preference = $this->preferenceClient()
            ->create($request);
        if (empty($preference->id)) {
            throw new RuntimeException(
                'Mercado Pago no devolvió una preferencia válida.'
            );
        }

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
/*Construir Request*/
private function construirRequest(
    Payment $payment
):array{
    return[
        'items' => $this->construirItems($payment),
        'prayer' => $this->construirPrayer($payment),
        'external_reference' => $payment->reference,
        'statement_descriptor' => self::STATEMENT_DESCRIPTOR,
        'binary_mode' => false,
        'metadata' => $this->construirMetadata(
            $payment
        ),
        'back_urls' => $this->construirBackUrls(),
        'notification_url' => route('mp.webhook'),
        'auto_return' => 'approved',
        'purpose' => 'wallet_purchase',
    ];

}
/* construir Metadata */
private function construirMetadata(
    Payment $payment
): array{
    return [
        'payment_id' => $payment->id,
        'order_id' =>$payment->order->id,
    ];
}
private function construirBackUrls(): array{
    return[
        'success' => route('mp.success'),
        'failure' => route('mp.failure'),
        'pending' => route('mp.pending'),
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
                'category_id' => self::CATEGORY,
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
private function accessToken(): string
{

    return config(

        'mercadopago.access_token'

    );

}
}
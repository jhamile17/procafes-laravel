<?php

declare(strict_types=1);

namespace App\Services\Pasarelas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

final class MercadoPagoSignatureService
{
    /*
    |--------------------------------------------------------------------------
    | Validar firma del Webhook
    |--------------------------------------------------------------------------
    */

    public function validar(
        Request $request
    ): bool {

        $secret = config('mercadopago.webhook_secret');
        Log::info('WEBHOOK CONFIG', [
        'secret' => $secret,
        'blank' => blank($secret),
        'signature' => $request->header('x-signature'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Si no existe secret, permitir (Sandbox)
        |--------------------------------------------------------------------------
        */

        if (blank($secret)) {

            Log::warning(
                'Webhook Secret no configurado. Validación omitida.'
            );

            return true;

        }

        /*
        |--------------------------------------------------------------------------
        | Obtener firma enviada por Mercado Pago
        |--------------------------------------------------------------------------
        */

        $signature = $request->header('x-signature');

        if (blank($signature)) {

            Log::warning(
                'Webhook recibido sin firma.'
            );

            return false;

        }

        /*
        |--------------------------------------------------------------------------
        | TODO
        |--------------------------------------------------------------------------
        |
        | Aquí posteriormente implementaremos la validación oficial
        | de Mercado Pago utilizando:
        |
        | - x-signature
        | - x-request-id
        | - data.id
        |
        | Por ahora verificamos únicamente que exista la firma.
        |
        */

        return true;

    }
}
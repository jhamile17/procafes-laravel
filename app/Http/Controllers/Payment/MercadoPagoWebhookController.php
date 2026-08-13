<?php

declare(strict_types=1);

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\Pasarelas\MercadoPagoWebhookService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MercadoPagoWebhookController extends Controller
{
    public function __construct(
        protected MercadoPagoWebhookService $webhookService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Webhook Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function handle(
        Request $request
    ): JsonResponse {
        return $this->webhookService
            ->procesar(
                $request
            );

    }
}
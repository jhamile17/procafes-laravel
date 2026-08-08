<?php

declare(strict_types=1);

namespace App\Services\Facturacion\Client;

use App\Services\Facturacion\Exceptions\NubeFactException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\Response;

final class ClienteNubeFactService
{
    public function __construct(
        protected HttpFactory $http,
    ) {
    }

    /*Enviar comprobante*/

    public function enviar(
        array $payload
    ): array {

        $response = $this->request($payload);

        return $this->decode($response);

    }

    /* Ejecutar petición HTTP*/
    protected function request(
        array $payload
    ): Response {

        $url = config('services.nubefact.url');
        $token = config('services.nubefact.token');

        if (blank($url) || blank($token)) {

            throw new NubeFactException(
                'La configuración de NubeFact es inválida.'
            );

        }

        try {

            return $this->http
                ->withToken($token)
                ->acceptJson()
                ->timeout(30)
                ->post($url, $payload);

        } catch (\Throwable $e) {

            throw new NubeFactException(
                'No fue posible conectar con NubeFact.',
                previous: $e
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Decodificar respuesta
    |--------------------------------------------------------------------------
    */

    protected function decode(
    Response $response
    ): array {
        if (! $response->successful()) {
            throw new NubeFactException(
                'NubeFact respondió con un error HTTP (' .
                $response->status() .
                ').'
            );
        }
        $data = $response->json();
        if (! is_array($data)) {

            throw new NubeFactException(
                'NubeFact devolvió una respuesta inválida.'
            );
        }
        return $data;
    }
}
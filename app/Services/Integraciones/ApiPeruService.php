<?php

declare(strict_types=1);

namespace App\Services\Integraciones;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ApiPeruService
{
    /*
    |--------------------------------------------------------------------------
    | Configuración
    |--------------------------------------------------------------------------
    */

    protected string $baseUrl;

    protected string $token;

    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
        $this->baseUrl = rtrim(

            config('APIS_PERU_URL'),

            '/'

        );

        $this->token = config(

            'APIS_PERU_TOKEN'

        );

        if (

            blank($this->baseUrl) ||

            blank($this->token)

        ) {

            throw new RuntimeException(

                'La configuración de ApiPeru no existe.'

            );

        }
    }
        /*
    |--------------------------------------------------------------------------
    | Cliente HTTP
    |--------------------------------------------------------------------------
    */

    protected function http(): PendingRequest
    {
        return Http::acceptJson()

            ->withToken(

                $this->token

            )

            ->timeout(15)

            ->connectTimeout(5);
    }
        /*
    |--------------------------------------------------------------------------
    | Realizar petición
    |--------------------------------------------------------------------------
    */

    protected function request(
        string $endpoint,
        array $payload
    ): array {

        $response = $this->http()

            ->post(

                "{$this->baseUrl}/{$endpoint}",

                $payload

            );

        if ($response->failed()) {

            $json = $response->json();

            return [

                'success' => false,

                'message' =>

                    $json['message']

                    ?? 'No fue posible consultar ApiPeru.',

                'data' => null,

            ];

        }

        $json = $response->json();

        if (

            ! ($json['success'] ?? false)

        ) {

            return [

                'success' => false,

                'message' =>

                    $json['message']

                    ?? 'La consulta no devolvió resultados.',

                'data' => null,

            ];

        }

        return [

            'success' => true,

            'message' => null,

            'data' =>

                $json['data'] ?? [],

        ];

    }
        /*
    |--------------------------------------------------------------------------
    | Consultar DNI
    |--------------------------------------------------------------------------
    */

    public function consultarDni(
        string $dni
    ): array {

        if (

            ! preg_match(

                '/^\d{8}$/',

                $dni

            )

        ) {

            return [

                'success' => false,

                'message' => 'El DNI no es válido.',

                'data' => null,

            ];

        }

        $response = $this->request(

            endpoint: 'dni',

            payload: [

                'dni' => $dni,

            ]

        );

        if (! $response['success']) {

            return $response;

        }

        return [

            'success' => true,

            'message' => null,

            'data' => $this->mapDni(

                $response['data']

            ),

        ];

    }
        /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    public function consultarRuc(
        string $ruc
    ): array {

        if (

            ! preg_match(

                '/^\d{11}$/',

                $ruc

            )

        ) {

            return [

                'success' => false,

                'message' => 'El RUC no es válido.',

                'data' => null,

            ];

        }

        $response = $this->request(

            endpoint: 'ruc',

            payload: [

                'ruc' => $ruc,

            ]

        );

        if (! $response['success']) {

            return $response;

        }

        return [

            'success' => true,

            'message' => null,

            'data' => $this->mapRuc(

                $response['data']

            ),

        ];

    }
        /*
    |--------------------------------------------------------------------------
    | Normalizar DNI
    |--------------------------------------------------------------------------
    */

    protected function mapDni(
        array $data
    ): array {

        return [

            'numero_documento' =>

                $data['numero']
                ?? $data['dni']
                ?? '',

            'nombre' =>

                $data['nombre_completo']

                ?? trim(

                    ($data['nombres'] ?? '') . ' ' .
                    ($data['apellido_paterno'] ?? '') . ' ' .
                    ($data['apellido_materno'] ?? '')

                ),

        ];

    }
        /*
    |--------------------------------------------------------------------------
    | Normalizar RUC
    |--------------------------------------------------------------------------
    */

    protected function mapRuc(
        array $data
    ): array {

        return [

            'numero_documento' =>

                $data['numero']
                ?? $data['ruc']
                ?? '',

            'razon_social' =>

                $data['nombre_o_razon_social']
                ?? $data['razon_social']
                ?? '',

            'direccion_fiscal' =>

                $data['direccion']
                ?? $data['direccion_completa']
                ?? '',

        ];

    }
}
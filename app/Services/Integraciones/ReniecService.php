<?php

namespace App\Services\Integraciones;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class ReniecService
{
    protected string $baseUrl;

    protected string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim(
            env('APIS_PERU_URL'),
            '/'
        );

        $this->token = env('APIS_PERU_TOKEN');
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar DNI
    |--------------------------------------------------------------------------
    */

    public function consultarDni(string $dni): array
    {
        $dni = trim($dni);

        if (! preg_match('/^\d{8}$/', $dni)) {
            return [
                'success' => false,
                'message' => 'El DNI debe contener 8 dígitos.',
                'data' => null,
            ];
        }

        try {

            $response = Http::acceptJson()
                ->withToken($this->token)
                ->timeout(15)
                ->get("{$this->baseUrl}/dni/{$dni}");

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => 'No fue posible consultar el DNI.',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => true,
                'message' => null,
                'data' => $response->json(),
            ];

        } catch (RequestException $exception) {

            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null,
            ];

        } catch (\Throwable $exception) {

            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null,
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    public function consultarRuc(string $ruc): array
    {
        $ruc = trim($ruc);

        if (! preg_match('/^\d{11}$/', $ruc)) {
            return [
                'success' => false,
                'message' => 'El RUC debe contener 11 dígitos.',
                'data' => null,
            ];
        }

        try {

            $response = Http::acceptJson()
                ->withToken($this->token)
                ->timeout(15)
                ->get("{$this->baseUrl}/ruc/{$ruc}");

            if ($response->failed()) {
                return [
                    'success' => false,
                    'message' => 'No fue posible consultar el RUC.',
                    'data' => $response->json(),
                ];
            }

            return [
                'success' => true,
                'message' => null,
                'data' => $response->json(),
            ];

        } catch (RequestException $exception) {

            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null,
            ];

        } catch (\Throwable $exception) {

            return [
                'success' => false,
                'message' => $exception->getMessage(),
                'data' => null,
            ];
        }
    }
}
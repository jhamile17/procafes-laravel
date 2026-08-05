<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class LocationIQService
{
    /**
     * --------------------------------------------------------------------------
     * Configuración
     * --------------------------------------------------------------------------
     */
    private const COUNTRY = 'pe';

    private const LIMIT = 5;

    /**
     * --------------------------------------------------------------------------
     * Buscar direcciones
     * --------------------------------------------------------------------------
     */
    public function search(string $query): array
    {
        $query = trim($query);

        if (mb_strlen($query) < 2) {
            return [];
        }

        try {

            $response = Http::timeout(8)
                ->retry(2, 300)
                ->get(
                    'https://us1.locationiq.com/v1/autocomplete',
                    [
                        'key' => config('services.locationiq.api_key'),

                        'q' => $query,

                        'limit' => self::LIMIT,

                        'format' => 'json',

                        'countrycodes' => self::COUNTRY,

                        'accept-language' => 'es',

                        'addressdetails' => 1,

                        'dedupe' => 1,
                    ]
                );

        } catch (Throwable $e) {

            report($e);

            return [];

        }

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json())

            ->map(fn(array $item) => $this->normalizeResult($item))

            ->filter(fn(array $item) =>

                ! empty($item['direccion']) &&
                ! empty($item['departamento']) &&
                ! empty($item['provincia']) &&
                ! empty($item['distrito'])

            )

            ->values()

            ->toArray();
    }

    /**
     * --------------------------------------------------------------------------
     * Normalizar resultado
     * --------------------------------------------------------------------------
     */
    private function normalizeResult(array $item): array
    {
        $address = $item['address'] ?? [];

        /*
        |--------------------------------------------------------------------------
        | Texto mostrado al usuario
        |--------------------------------------------------------------------------
        */

        $label = trim($item['display_name'] ?? '');

        $label = preg_replace(
            '/,\s*Perú$/iu',
            '',
            $label
        );

        $label = preg_replace(
            '/\s+/',
            ' ',
            $label
        );

        /*
        |--------------------------------------------------------------------------
        | Dirección
        |--------------------------------------------------------------------------
        */

        $direccion = collect([

            $address['road'] ?? null,

            $address['house_number'] ?? null,

            $address['residential'] ?? null,

        ])

        ->filter()

        ->implode(' ');

        if (blank($direccion)) {

            $direccion = $label;

        }

        /*
        |--------------------------------------------------------------------------
        | Departamento
        |--------------------------------------------------------------------------
        */

        $departamento =

            $address['state']

            ?? $address['region']

            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Provincia
        |--------------------------------------------------------------------------
        */

        $provincia =

            $address['county']

            ?? $address['state_district']

            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Distrito
        |--------------------------------------------------------------------------
        */

        $distrito =

            $address['city_district']

            ?? $address['suburb']

            ?? $address['city']

            ?? $address['town']

            ?? $address['village']

            ?? null;

        return [

            /*
            |--------------------------------------------------------------------------
            | Mostrar al usuario
            |--------------------------------------------------------------------------
            */

            'label' => $label,

            /*
            |--------------------------------------------------------------------------
            | ShippingAddress
            |--------------------------------------------------------------------------
            */

            'direccion' => $direccion,

            'departamento' => $departamento,

            'provincia' => $provincia,

            'distrito' => $distrito,

            /*
            |--------------------------------------------------------------------------
            | Coordenadas
            |--------------------------------------------------------------------------
            */

            'latitude' => isset($item['lat'])
                ? (float) $item['lat']
                : null,

            'longitude' => isset($item['lon'])
                ? (float) $item['lon']
                : null,

        ];
    }
}
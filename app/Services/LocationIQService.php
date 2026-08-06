<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Throwable;

class LocationIQService
{
    /*
    |--------------------------------------------------------------------------
    | Configuración
    |--------------------------------------------------------------------------
    */

    private const COUNTRY = 'pe';

    private const LIMIT = 5;

    /*
    |--------------------------------------------------------------------------
    | Buscar direcciones
    |--------------------------------------------------------------------------
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

                        'countrycodes' => self::COUNTRY,

                        'limit' => self::LIMIT,

                        'format' => 'json',

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

            ->map(fn (array $item) => $this->normalizeResult($item))

            ->filter(fn (array $item) =>

                filled($item['direccion']) &&
                filled($item['departamento']) &&
                filled($item['provincia']) &&
                filled($item['distrito'])

            )

            /*
            |--------------------------------------------------------------------------
            | Priorizar resultados de la zona de trabajo
            |--------------------------------------------------------------------------
            */

            ->sortByDesc(function (array $item) {

                $text = mb_strtolower($item['label']);

                return str_contains($text, 'pichanaqui')
                    || str_contains($text, 'junín')
                    || str_contains($text, 'chanchamayo');

            })

            ->values()

            ->toArray();
    }

    /*
    |--------------------------------------------------------------------------
    | Normalizar resultado
    |--------------------------------------------------------------------------
    */

    private function normalizeResult(array $item): array
    {
        $address = $item['address'] ?? [];

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

        /*
        |--------------------------------------------------------------------------
        | Dirección corta
        |--------------------------------------------------------------------------
        */

        $direccion = collect([

            $address['road'] ?? null,

            $address['house_number'] ?? null,

        ])

        ->filter()

        ->implode(' ');

        if (blank($direccion)) {

            $direccion = collect([

                $address['pedestrian'] ?? null,

                $address['square'] ?? null,

                $address['neighbourhood'] ?? null,

                $address['residential'] ?? null,

                $address['amenity'] ?? null,

                $address['building'] ?? null,

            ])

            ->filter()

            ->implode(' ');

        }

        if (blank($direccion)) {

            $direccion = collect([

                $distrito,

                $provincia,

            ])

            ->filter()

            ->implode(', ');

        }

        /*
        |--------------------------------------------------------------------------
        | Texto mostrado al usuario
        |--------------------------------------------------------------------------
        */

        $label = collect([

            $direccion,

            collect([

                $distrito,

                $provincia,

            ])

            ->filter()

            ->implode(', '),

        ])

        ->filter()

        ->implode(' · ');

        return [

            /*
            |--------------------------------------------------------------------------
            | Mostrar al usuario
            |--------------------------------------------------------------------------
            */

            'label' => $label,

            /*
            |--------------------------------------------------------------------------
            | Guardar en ShippingAddress
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
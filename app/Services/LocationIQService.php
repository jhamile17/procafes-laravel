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

            ->map(
                fn (array $item) =>
                    $this->normalizeResult($item)
            )

            /*
            |--------------------------------------------------------------------------
            | Solo descartamos resultados que realmente no tienen
            | información útil para representar una ubicación.
            |--------------------------------------------------------------------------
            */

            ->filter(
                fn (array $item) =>
                    filled($item['direccion']) ||
                    filled($item['distrito']) ||
                    filled($item['provincia']) ||
                    filled($item['departamento'])
            )

            /*
            |--------------------------------------------------------------------------
            | Priorizar Pichanaqui sin bloquear otras ubicaciones
            |--------------------------------------------------------------------------
            */

            ->sortByDesc(
                function (array $item) {

                    $text = mb_strtolower(
                        $item['label'] ?? ''
                    );

                    if (
                        str_contains($text, 'pichanaqui') ||
                        str_contains($text, 'pichanaki')
                    ) {
                        return 3;
                    }

                    if (
                        str_contains($text, 'chanchamayo')
                    ) {
                        return 2;
                    }

                    if (
                        str_contains($text, 'junín') ||
                        str_contains($text, 'junin')
                    ) {
                        return 1;
                    }

                    return 0;
                }
            )

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
        | Número
        |--------------------------------------------------------------------------
        */

        $numero = $address['house_number']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Dirección
        |--------------------------------------------------------------------------
        |
        | Aquí NO incluimos house_number porque tu formulario
        | tiene un campo separado para número / interior.
        |
        */

        $direccion = collect([

            $address['road'] ?? null,

            $address['pedestrian'] ?? null,

            $address['square'] ?? null,

            $address['neighbourhood'] ?? null,

            $address['residential'] ?? null,

            $address['amenity'] ?? null,

            $address['building'] ?? null,

        ])

            ->filter()

            ->unique()

            ->implode(' ');

        /*
        |--------------------------------------------------------------------------
        | Si no encontramos una calle o dirección específica,
        | usamos una ubicación disponible.
        |--------------------------------------------------------------------------
        */

        if (blank($direccion)) {

            $direccion = collect([

                $distrito,

                $provincia,

                $departamento,

            ])

                ->filter()

                ->unique()

                ->implode(', ');
        }

        /*
        |--------------------------------------------------------------------------
        | Texto mostrado al usuario
        |--------------------------------------------------------------------------
        */

        $ubicacion = collect([

            $distrito,

            $provincia,

            $departamento,

        ])

            ->filter()

            ->unique()

            ->implode(', ');

        $label = collect([

            $direccion,

            $numero,

            $ubicacion,

        ])

            ->filter()

            ->implode(' · ');

        /*
        |--------------------------------------------------------------------------
        | Resultado
        |--------------------------------------------------------------------------
        */

        return [

            /*
            |--------------------------------------------------------------------------
            | Mostrar al usuario
            |--------------------------------------------------------------------------
            */

            'label' => $label,

            /*
            |--------------------------------------------------------------------------
            | Dirección
            |--------------------------------------------------------------------------
            */

            'direccion' => $direccion,

            'numero' => $numero,

            /*
            |--------------------------------------------------------------------------
            | Ubicación administrativa
            |--------------------------------------------------------------------------
            */

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
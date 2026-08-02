<?php
declare(strict_types=1);
namespace App\Services;
use Illuminate\Support\Facades\Http;

class LocationIQService
{
    /*
    |--------------------------------------------------------------------------
    | Buscar direcciones
    |--------------------------------------------------------------------------
    */

    public function search(
        string $query
    ): array {

        $query = trim($query);

        if (mb_strlen($query) < 2) {
            return [];
        }

        $response = Http::get(
            'https://us1.locationiq.com/v1/autocomplete',
            [
                'key' => config('services.locationiq.api_key'),

                'q' => $query,

                'limit' => 5,

                'format' => 'json',

                'countrycodes' => 'pe',

                'accept-language' => 'es',

                'addressdetails' => 1,

                'dedupe' => 1,
            ]
        );

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json())

            ->map(function (array $item): array {

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
                    $address['house_number'] ?? null,

                ])

                ->filter()

                ->implode(' ');

                /*
                |--------------------------------------------------------------------------
                | Si no existe road usamos el nombre completo
                |--------------------------------------------------------------------------
                */

                if (empty($direccion)) {

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

                    'latitude' => (float) ($item['lat'] ?? 0),

                    'longitude' => (float) ($item['lon'] ?? 0),

                ];

            })

            ->filter(function (array $item): bool {

                return ! empty($item['direccion'])
                    && ! empty($item['departamento'])
                    && ! empty($item['provincia'])
                    && ! empty($item['distrito']);

            })

            ->values()

            ->toArray();

    }
}
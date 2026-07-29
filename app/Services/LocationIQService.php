<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class LocationIQService
{
    public function search(string $query): array
    {
        $query = trim($query);

        // Buscar desde 2 caracteres
        if (mb_strlen($query) < 2) {
            return [];
        }

        $response = Http::get(
            'https://us1.locationiq.com/v1/autocomplete',
            [
                'key'               => config('services.locationiq.api_key'),
                'q'                 => $query,
                'limit'             => 5,
                'format'            => 'json',
                'countrycodes'      => 'pe',
                'accept-language'   => 'es',
                'dedupe'            => 1,
                'addressdetails'    => 1,
            ]
        );

        if (! $response->successful()) {
            return [];
        }

        return collect($response->json())
            ->map(function ($item) {

                $label = trim($item['display_name'] ?? '');

                // Elimina ", Perú" del final
                $label = preg_replace('/,\s*Perú$/iu', '', $label);

                // Elimina espacios repetidos
                $label = preg_replace('/\s+/', ' ', $label);

                return [
                    'label' => $label,
                    'lat'   => (float) ($item['lat'] ?? 0),
                    'lon'   => (float) ($item['lon'] ?? 0),
                ];
            })
            ->filter(fn ($item) => ! empty($item['label']))
            ->values()
            ->toArray();
    }
}
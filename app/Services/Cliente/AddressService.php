<?php

declare(strict_types=1);

namespace App\Services\Cliente;

use App\Models\ShippingAddress;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AddressService
{
    /*
    |--------------------------------------------------------------------------
    | Obtener todas las direcciones del usuario
    |--------------------------------------------------------------------------
    */

    public function obtenerDirecciones(int $userId): Collection
    {
        return ShippingAddress::query()
            ->where('user_id', $userId)
            ->orderByDesc('es_principal')
            ->orderByDesc('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener dirección principal
    |--------------------------------------------------------------------------
    */

    public function obtenerPrincipal(int $userId): ?ShippingAddress
    {
        return ShippingAddress::query()
            ->where('user_id', $userId)
            ->principal()
            ->first();
    }

    /*
    |--------------------------------------------------------------------------
    | Crear dirección
    |--------------------------------------------------------------------------
    */

    public function crearDireccion(
        int $userId,
        array $data
    ): ShippingAddress {

        return DB::transaction(function () use ($userId, $data) {

            $esPrincipal = ! ShippingAddress::query()
                ->where('user_id', $userId)
                ->exists();

            if ($esPrincipal) {

                ShippingAddress::query()
                    ->where('user_id', $userId)
                    ->update([
                        'es_principal' => false,
                    ]);

            }

            return ShippingAddress::create([

                'user_id' => $userId,

                'alias' => $data['alias'] ?? 'Mi dirección',

                'direccion' => $data['direccion'],

                'departamento' => $data['departamento'],

                'provincia' => $data['provincia'],

                'distrito' => $data['distrito'],

                'referencia' => $data['referencia'] ?? null,

                'latitude' => $data['latitude'] ?? null,

                'longitude' => $data['longitude'] ?? null,

                'es_principal' => $esPrincipal,

            ]);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Marcar dirección principal
    |--------------------------------------------------------------------------
    */

    public function establecerPrincipal(
        ShippingAddress $address
    ): ShippingAddress {

        DB::transaction(function () use ($address) {

            ShippingAddress::query()
                ->where('user_id', $address->user_id)
                ->update([
                    'es_principal' => false,
                ]);

            $address->update([
                'es_principal' => true,
            ]);

        });

        return $address->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar dirección
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        ShippingAddress $address
    ): void {

        $eraPrincipal = $address->es_principal;

        $userId = $address->user_id;

        $address->delete();

        if ($eraPrincipal) {

            $nuevaPrincipal = ShippingAddress::query()
                ->where('user_id', $userId)
                ->first();

            if ($nuevaPrincipal) {

                $nuevaPrincipal->update([
                    'es_principal' => true,
                ]);

            }
        }
    }
}
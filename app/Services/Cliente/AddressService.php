<?php
declare(strict_types=1);
namespace App\Services\Cliente;
use App\Models\ShippingAddress;
use Illuminate\Support\Facades\DB;

class AddressService
{
    /*Obtener dirección del usuario*/

    public function obtenerDireccion(
        int $userId
    ): ?ShippingAddress {
        return ShippingAddress::query()
            ->where('user_id', $userId)
            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Crear o actualizar dirección
    |--------------------------------------------------------------------------
    */

    public function guardar(
        int $userId,
        array $data
    ): ShippingAddress {

        return DB::transaction(function () use (
            $userId,
            $data
        ) {

            $address = ShippingAddress::query()

                ->where('user_id', $userId)

                ->first();

            if (! $address) {

                return ShippingAddress::create([

                    'user_id' => $userId,

                    'direccion' => $data['direccion'],

                    'departamento' => $data['departamento'],

                    'provincia' => $data['provincia'],

                    'distrito' => $data['distrito'],

                    'latitude' => $data['latitude'],

                    'longitude' => $data['longitude'],

                ]);

            }

            $address->update([

                'direccion' => $data['direccion'],

                'departamento' => $data['departamento'],

                'provincia' => $data['provincia'],

                'distrito' => $data['distrito'],

                'latitude' => $data['latitude'],

                'longitude' => $data['longitude'],

            ]);

            return $address->fresh();

        });

    }

    /*Eliminar dirección*/

    public function eliminar(
        int $userId
    ): void {

        ShippingAddress::query()

            ->where('user_id', $userId)

            ->delete();

    }
}
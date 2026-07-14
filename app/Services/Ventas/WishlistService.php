<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Wishlist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WishlistService
{
    /*
    |--------------------------------------------------------------------------
    | Agregar producto a favoritos
    |--------------------------------------------------------------------------
    */

    public function agregar(
        int $userId,
        int $productId
    ): Wishlist {

        if ($this->existe($userId, $productId)) {

            throw new RuntimeException(
                'El producto ya se encuentra en la lista de favoritos.'
            );

        }

        return DB::transaction(function () use (
            $userId,
            $productId
        ) {

            return Wishlist::create([
                'user_id'    => $userId,
                'product_id' => $productId,
            ]);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar producto de favoritos
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        int $userId,
        int $productId
    ): bool {

        $wishlist = Wishlist::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (! $wishlist) {

            throw new RuntimeException(
                'El producto no existe en la lista de favoritos.'
            );

        }

        return (bool) $wishlist->delete();

    }

    /*
    |--------------------------------------------------------------------------
    | Alternar favorito (Agregar / Eliminar)
    |--------------------------------------------------------------------------
    */

    public function toggle(
        int $userId,
        int $productId
    ): bool {

        if ($this->existe($userId, $productId)) {

            $this->eliminar(
                $userId,
                $productId
            );

            return false;

        }

        $this->agregar(
            $userId,
            $productId
        );

        return true;

    }

    /*
    |--------------------------------------------------------------------------
    | Verificar existencia
    |--------------------------------------------------------------------------
    */

    public function existe(
        int $userId,
        int $productId
    ): bool {

        return Wishlist::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener favoritos
    |--------------------------------------------------------------------------
    */

    public function obtenerFavoritos(
        int $userId
    ): Collection {

        return Wishlist::query()
            ->with([
                'product.category',
                'product.brand',
            ])
            ->where('user_id', $userId)
            ->latest()
            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Contar favoritos
    |--------------------------------------------------------------------------
    */

    public function contarFavoritos(
        int $userId
    ): int {

        return Wishlist::query()
            ->where('user_id', $userId)
            ->count();

    }

    /*
    |--------------------------------------------------------------------------
    | Sincronizar favoritos desde LocalStorage
    |--------------------------------------------------------------------------
    |
    | Inserta únicamente los productos que todavía no existen
    | en la lista del usuario.
    |
    */

    public function sincronizar(
        int $userId,
        array $favorites
    ): void {

        DB::transaction(function () use (
            $userId,
            $favorites
        ) {

            foreach ($favorites as $productId) {

                if (! $this->existe($userId, (int) $productId)) {

                    Wishlist::create([
                        'user_id'    => $userId,
                        'product_id' => (int) $productId,
                    ]);

                }

            }

        });

    }
}
<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WishlistService
{
    /*
    |--------------------------------------------------------------------------
    | Obtener favoritos
    |--------------------------------------------------------------------------
    */

    public function obtenerFavoritos(int $userId): Collection
    {
        $products = Wishlist::query()
            ->with([
                'product.category',
                'product.brand',
            ])
            ->where('user_id', $userId)
            ->latest()
            ->get()
            ->pluck('product');

        return $this->formatearProductos($products);
    }

    /*
    |--------------------------------------------------------------------------
    | Formatear productos
    |--------------------------------------------------------------------------
    */

    public function formatearProductos(
        Collection $products
    ): Collection {

        return $products->map(function (Product $product): object {

            return (object) [

                'product_id' => $product->id,

                'name' => $product->name,

                'description' => $product->description,

                'price' => $product->sale_price,

                'formatted_price' => $product->precio_formateado,

                'image' => $product->image_url,

                'stock' => $product->stock,

                'stock_status' => $product->stock_status,

                'stock_badge' => $product->stock_badge,

                'brand' => $product->brand?->name,

                'category' => $product->category?->name,

                'status' => $product->status,

            ];

        });

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
    | Verificar favorito
    |--------------------------------------------------------------------------
    */

    public function esFavorito(
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
    | Agregar favorito
    |--------------------------------------------------------------------------
    */

    public function agregarFavorito(
        int $userId,
        int $productId
    ): Wishlist {

        if ($this->esFavorito($userId, $productId)) {

            throw new RuntimeException(
                'El producto ya se encuentra en la lista de favoritos.'
            );

        }

        return DB::transaction(function () use (
            $userId,
            $productId
        ) {

            return Wishlist::create([

                'user_id' => $userId,

                'product_id' => $productId,

            ]);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar favorito
    |--------------------------------------------------------------------------
    */

    public function eliminarFavorito(
        int $userId,
        int $productId
    ): void {

        Wishlist::query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

    }

    /*
    |--------------------------------------------------------------------------
    | Alternar favorito
    |--------------------------------------------------------------------------
    */

    public function toggle(
        int $userId,
        int $productId
    ): bool {

        if ($this->esFavorito($userId, $productId)) {

            $this->eliminarFavorito(
                $userId,
                $productId
            );

            return false;

        }

        $this->agregarFavorito(
            $userId,
            $productId
        );

        return true;

    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar favoritos
    |--------------------------------------------------------------------------
    */

    public function vaciarFavoritos(
        int $userId
    ): void {

        Wishlist::query()
            ->where('user_id', $userId)
            ->delete();

    }
}
<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReviewService
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Crear reseña
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): Review
    {
        $existe = Review::query()

            ->where('user_id', $datos['user_id'])

            ->where('product_id', $datos['product_id'])

            ->exists();

        if ($existe) {

            throw new RuntimeException(
                'El usuario ya registró una reseña para este producto.'
            );

        }

        return DB::transaction(function () use ($datos) {

            return Review::create([

                'user_id' => $datos['user_id'],

                'product_id' => $datos['product_id'],

                'rating' => $datos['rating'],

                'comment' => $datos['comment'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar reseña
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        Review $review,
        array $datos
    ): Review {

        DB::transaction(function () use ($review, $datos) {

            $review->update([

                'rating' => $datos['rating'],

                'comment' => $datos['comment'] ?? null,

            ]);

        });

        return $review->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar reseña
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        Review $review
    ): bool {

        if (! $review->delete()) {

            throw new RuntimeException(
                'No fue posible eliminar la reseña.'
            );

        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener promedio de un producto
    |--------------------------------------------------------------------------
    */

    public function promedioProducto(
        int $productId
    ): float {

        return round(

            (float) Review::query()

                ->where('product_id', $productId)

                ->avg('rating'),

            2

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener reseñas de un producto
    |--------------------------------------------------------------------------
    */

    public function obtenerProducto(
        int $productId
    ): Collection {

        return Review::query()

            ->with('user')

            ->where('product_id', $productId)

            ->latest()

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener reseñas de un usuario
    |--------------------------------------------------------------------------
    */

    public function obtenerUsuario(
        int $userId
    ): Collection {

        return Review::query()

            ->with('product')

            ->where('user_id', $userId)

            ->latest()

            ->get();
    }
}
<?php

declare(strict_types=1);

namespace App\Services\Ventas;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SessionWishlistService
{
    /**
     * Clave utilizada en la sesión.
     */
    private const SESSION_KEY = 'wishlist';

    /*
    |--------------------------------------------------------------------------
    | Obtener favoritos
    |--------------------------------------------------------------------------
    */

    public function obtener(Request $request): Collection
    {
        return collect(
            $request->session()->get(self::SESSION_KEY, [])
        )->values();
    }

    /*
    |--------------------------------------------------------------------------
    | Contar favoritos
    |--------------------------------------------------------------------------
    */

    public function contar(Request $request): int
    {
        return $this->obtener($request)->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar si existe
    |--------------------------------------------------------------------------
    */

    public function existe(
        Request $request,
        int $productId
    ): bool {

        return $this->obtener($request)
            ->contains($productId);

    }

    /*
    |--------------------------------------------------------------------------
    | Agregar favorito
    |--------------------------------------------------------------------------
    */

    public function agregar(
        Request $request,
        int $productId
    ): void {

        $favorites = $this->obtener($request)
            ->all();

        if (!in_array($productId, $favorites, true)) {

            $favorites[] = $productId;

            $request->session()->put(
                self::SESSION_KEY,
                array_values($favorites)
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar favorito
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        Request $request,
        int $productId
    ): void {

        $favorites = $this->obtener($request)
            ->reject(fn (int $id) => $id === $productId)
            ->values()
            ->all();

        $request->session()->put(
            self::SESSION_KEY,
            $favorites
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Alternar favorito
    |--------------------------------------------------------------------------
    */

    public function toggle(
        Request $request,
        int $productId
    ): bool {

        if ($this->existe($request, $productId)) {

            $this->eliminar(
                $request,
                $productId
            );

            return false;

        }

        $this->agregar(
            $request,
            $productId
        );

        return true;

    }

    /*
    |--------------------------------------------------------------------------
    | Vaciar favoritos
    |--------------------------------------------------------------------------
    */

    public function vaciar(Request $request): void
    {
        $request->session()->forget(
            self::SESSION_KEY
        );
    }
}
<?php

declare(strict_types=1);

namespace App\Services\Catalogo;

use App\Models\HistorialPrecio;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HistorialPrecioService
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
    | Registrar cambio de precio
    |--------------------------------------------------------------------------
    */

    public function registrarCambio(array $datos): HistorialPrecio
    {
        return DB::transaction(function () use ($datos) {

            return HistorialPrecio::create([

                'product_id' => $datos['product_id'],

                'usuario_id' => $datos['usuario_id'],

                'tipo_precio' => $datos['tipo_precio'],

                'precio_anterior' => $datos['precio_anterior'],

                'precio_nuevo' => $datos['precio_nuevo'],

                'motivo' => $datos['motivo'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener historial de un producto
    |--------------------------------------------------------------------------
    */

    public function obtenerHistorial(
        int $productId
    ): Collection {

        return HistorialPrecio::query()

            ->with('usuario')

            ->where('product_id', $productId)

            ->latest('created_at')

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener último cambio de precio
    |--------------------------------------------------------------------------
    */

    public function ultimoCambio(
        int $productId
    ): ?HistorialPrecio {

        return HistorialPrecio::query()

            ->with('usuario')

            ->where('product_id', $productId)

            ->latest('created_at')

            ->first();
    }
}
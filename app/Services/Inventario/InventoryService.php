<?php

namespace App\Services\Inventario;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class InventoryService
{
    /*
    |--------------------------------------------------------------------------
    | Entrada de inventario
    |--------------------------------------------------------------------------
    */

    public function entrada(
        Product $product,
        int $cantidad
    ): Product {

        if ($cantidad <= 0) {
            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );
        }

        return DB::transaction(function () use ($product, $cantidad) {

            $product->increment('stock', $cantidad);

            return $product->fresh();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Salida de inventario
    |--------------------------------------------------------------------------
    */

    public function salida(
        Product $product,
        int $cantidad
    ): Product {

        $this->validarStock($product, $cantidad);

        return DB::transaction(function () use ($product, $cantidad) {

            $product->decrement('stock', $cantidad);

            return $product->fresh();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Ajuste de inventario
    |--------------------------------------------------------------------------
    */

    public function ajuste(
        Product $product,
        int $nuevoStock
    ): Product {

        if ($nuevoStock < 0) {

            throw new RuntimeException(
                'El stock no puede ser negativo.'
            );

        }

        return DB::transaction(function () use ($product, $nuevoStock) {

            $product->update([
                'stock' => $nuevoStock
            ]);

            return $product->fresh();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener stock actual
    |--------------------------------------------------------------------------
    */

    public function obtenerStock(Product $product): int
    {
        return (int) $product->stock;
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar disponibilidad
    |--------------------------------------------------------------------------
    */

    public function tieneStock(
        Product $product,
        int $cantidad = 1
    ): bool {

        return $product->stock >= $cantidad;

    }

    /*
    |--------------------------------------------------------------------------
    | Validar stock
    |--------------------------------------------------------------------------
    */

    public function validarStock(
        Product $product,
        int $cantidad
    ): void {

        if ($cantidad <= 0) {

            throw new RuntimeException(
                'La cantidad debe ser mayor que cero.'
            );

        }

        if (!$this->tieneStock($product, $cantidad)) {

            throw new RuntimeException(
                'Stock insuficiente para realizar la operación.'
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Reponer stock mínimo
    |--------------------------------------------------------------------------
    */

    public function necesitaReposicion(Product $product): bool
    {
        return $product->stock <= $product->stock_minimo;
    }

    /*
    |--------------------------------------------------------------------------
    | Producto agotado
    |--------------------------------------------------------------------------
    */

    public function estaAgotado(Product $product): bool
    {
        return $product->stock <= 0;
    }
}
<?php

declare(strict_types=1);

namespace App\Services\Inventario;

use App\Models\MovimientoInventario;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MovimientoInventarioService
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
    | Registrar entrada de inventario
    |--------------------------------------------------------------------------
    */

    public function registrarEntrada(array $datos): MovimientoInventario
    {
        return DB::transaction(function () use ($datos) {

            return MovimientoInventario::create([

                'product_id' => $datos['product_id'],

                'tipo_movimiento_id' => $datos['tipo_movimiento_id'],

                'usuario_id' => $datos['usuario_id'] ?? null,

                'cantidad' => $datos['cantidad'],

                'stock_anterior' => $datos['stock_anterior'],

                'stock_actual' => $datos['stock_actual'],

                'motivo' => $datos['motivo'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar salida de inventario
    |--------------------------------------------------------------------------
    */

    public function registrarSalida(array $datos): MovimientoInventario
    {
        return DB::transaction(function () use ($datos) {

            return MovimientoInventario::create([

                'product_id' => $datos['product_id'],

                'tipo_movimiento_id' => $datos['tipo_movimiento_id'],

                'usuario_id' => $datos['usuario_id'] ?? null,

                'cantidad' => $datos['cantidad'],

                'stock_anterior' => $datos['stock_anterior'],

                'stock_actual' => $datos['stock_actual'],

                'motivo' => $datos['motivo'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar ajuste de inventario
    |--------------------------------------------------------------------------
    */

    public function registrarAjuste(array $datos): MovimientoInventario
    {
        return DB::transaction(function () use ($datos) {

            return MovimientoInventario::create([

                'product_id' => $datos['product_id'],

                'tipo_movimiento_id' => $datos['tipo_movimiento_id'],

                'usuario_id' => $datos['usuario_id'] ?? null,

                'cantidad' => $datos['cantidad'],

                'stock_anterior' => $datos['stock_anterior'],

                'stock_actual' => $datos['stock_actual'],

                'motivo' => $datos['motivo'] ?? null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener historial de un producto
    |--------------------------------------------------------------------------
    */

    public function obtenerHistorialProducto(
        int $productId
    ): Collection {

        return MovimientoInventario::query()

            ->with([
                'tipoMovimiento',
                'usuario',
            ])

            ->where(
                'product_id',
                $productId
            )

            ->latest()

            ->get();
    }
}
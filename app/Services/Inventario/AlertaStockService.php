<?php

declare(strict_types=1);

namespace App\Services\Inventario;

use App\Models\AlertaStock;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AlertaStockService
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
    | Crear alerta de stock
    |--------------------------------------------------------------------------
    */

    public function crearAlerta(array $datos): AlertaStock
    {
        return DB::transaction(function () use ($datos) {

            return AlertaStock::create([

                'product_id' => $datos['product_id'],

                'nivel_alerta_id' => $datos['nivel_alerta_id'],

                'stock_detectado' => $datos['stock_detectado'],

                'mensaje' => $datos['mensaje'],

                'enviado_correo' => false,

                'enviado_app' => false,

                'fecha_envio' => null,

            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener alertas pendientes
    |--------------------------------------------------------------------------
    */

    public function obtenerPendientes(): Collection
    {
        return AlertaStock::query()

            ->with([
                'product',
                'nivelAlerta',
            ])

            ->where('enviado_correo', false)

            ->orWhere('enviado_app', false)

            ->latest()

            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Marcar alerta como enviada
    |--------------------------------------------------------------------------
    */

    public function marcarEnviada(
        AlertaStock $alerta,
        bool $correo = true,
        bool $app = false
    ): AlertaStock {

        DB::transaction(function () use (
            $alerta,
            $correo,
            $app
        ) {

            $alerta->update([

                'enviado_correo' => $correo,

                'enviado_app' => $app,

                'fecha_envio' => now(),

            ]);

        });

        return $alerta->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener alertas de un producto
    |--------------------------------------------------------------------------
    */

    public function obtenerProducto(
        int $productId
    ): Collection {

        return AlertaStock::query()

            ->with('nivelAlerta')

            ->where(
                'product_id',
                $productId
            )

            ->latest()

            ->get();
    }
}
<?php

declare(strict_types=1);

namespace App\Services\Facturacion;

use App\Models\Comprobante;
use App\Models\EstadoComprobante;
use App\Models\Order;
use RuntimeException;

class ComprobanteService
{
    /*
    |--------------------------------------------------------------------------
    | Crear comprobante
    |--------------------------------------------------------------------------
    */

    public function crear(
        Order $order,
        array $data
    ): Comprobante {

        if ($order->comprobante()->exists()) {

            throw new RuntimeException(
                'El pedido ya tiene un comprobante.'
            );

        }

        return Comprobante::create([

            'order_id' => $order->id,

            'estado_comprobante_id' => $this->estadoPendiente()->id,

            'tipo_comprobante' => strtoupper(
                $data['tipo_comprobante']
            ),

            'tipo_documento' => strtoupper(
                $data['tipo_documento']
            ),

            'numero_documento' => trim(
                $data['numero_documento']
            ),

            'nombre' => $data['nombre'] ?? null,

            'razon_social' => $data['razon_social'] ?? null,

            'direccion_fiscal' => trim(
                $data['direccion_fiscal']
            ),

        ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener estado pendiente
    |--------------------------------------------------------------------------
    */

    public function estadoPendiente(): EstadoComprobante
    {
        return EstadoComprobante::query()

            ->where(
                'codigo',
                EstadoComprobante::PENDIENTE
            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Marcar emitido
    |--------------------------------------------------------------------------
    */

    public function marcarEmitido(
        Comprobante $comprobante
    ): void {

        $estado = EstadoComprobante::query()

            ->where(
                'codigo',
                EstadoComprobante::EMITIDO
            )

            ->firstOrFail();

        $comprobante->actualizarEstado(
            $estado
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Marcar anulado
    |--------------------------------------------------------------------------
    */

    public function marcarAnulado(
        Comprobante $comprobante
    ): void {

        $estado = EstadoComprobante::query()

            ->where(
                'codigo',
                EstadoComprobante::ANULADO
            )

            ->firstOrFail();

        $comprobante->actualizarEstado(
            $estado
        );

    }
}
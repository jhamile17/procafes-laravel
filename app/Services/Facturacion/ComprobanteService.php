<?php

declare(strict_types=1);

namespace App\Services\Facturacion;

use App\Models\Comprobante;
use App\Models\EstadoComprobante;
use App\Models\Order;
use RuntimeException;

class ComprobanteService
{
    /*Crear comprobante*/

    public function crear(
        Order $order,
        array $data
    ): Comprobante {

        $this->validarPedido($order);

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

            'direccion_fiscal' => isset($data['direccion_fiscal'])
                ? trim($data['direccion_fiscal'])
                : null,

        ])->load([

            'order',
            'estadoComprobante',

        ]);

    }

    /*Obtener comprobante por pedido*/

    public function obtenerPorPedido(
        Order $order
    ): Comprobante {
        return $order->comprobante()
            ->with([
                'estadoComprobante',
            ])
            ->firstOrFail();
    }

    /*Obtener estado pendiente*/

    public function estadoPendiente(): EstadoComprobante
    {
        return $this->obtenerEstado(
            EstadoComprobante::PENDIENTE
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Marcar emitido
    |--------------------------------------------------------------------------
    */

    public function marcarEmitido(
        Comprobante $comprobante
    ): Comprobante {

        $comprobante->actualizarEstado(

            $this->obtenerEstado(
                EstadoComprobante::EMITIDO
            )

        );

        return $comprobante->refresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Marcar anulado
    |--------------------------------------------------------------------------
    */

    public function marcarAnulado(
        Comprobante $comprobante
    ): Comprobante {

        $comprobante->actualizarEstado(

            $this->obtenerEstado(
                EstadoComprobante::ANULADO
            )

        );
        return $comprobante->refresh();
    }
    /*Obtener estado*/
    protected function obtenerEstado(
        string $codigo
    ): EstadoComprobante {

        return EstadoComprobante::query()

            ->where(
                'codigo',
                $codigo
            )
            ->firstOrFail();
    }
    /*Validar pedido*/
    protected function validarPedido(
        Order $order
    ): void {

        if ($order->comprobante()->exists()) {

            throw new RuntimeException(
                'El pedido ya tiene un comprobante.'
            );

        }

    }
}
<?php

declare(strict_types=1);

namespace App\Services\Facturacion\Payload;

use App\Models\Comprobante;
use App\Services\Facturacion\Support\NubeFact;

final class NubeFactPayloadBuilder
{
    public function build(Comprobante $comprobante): array
    {
        $order = $comprobante->order;

        return [

            /*
            |--------------------------------------------------------------------------
            | Documento
            |--------------------------------------------------------------------------
            */

            'operacion' => NubeFact::GENERAR_COMPROBANTE,

            'tipo_de_comprobante' => $comprobante->esFactura()
                ? NubeFact::FACTURA
                : NubeFact::BOLETA,

            // Cambia estas series por las tuyas
            'serie' => $comprobante->esFactura()
                ? 'FFF1'
                : 'BBB1',

            // Vacío = NubeFact asigna la numeración
            'numero' => '',

            'sunat_transaction' => NubeFact::VENTA_INTERNA,

            'fecha_de_emision' => now()->format('d-m-Y'),

            'moneda' => NubeFact::SOLES,

            // Evita emitir dos veces el mismo comprobante
            'codigo_unico' => $comprobante->order->numero_pedido,

            /*
            |--------------------------------------------------------------------------
            | Cliente
            |--------------------------------------------------------------------------
            */

            'cliente_tipo_de_documento' => $comprobante->usaRuc()
                ? NubeFact::RUC
                : NubeFact::DNI,

            'cliente_numero_de_documento' => $comprobante->numero_documento,

            'cliente_denominacion' => $comprobante->denominacion(),

            'cliente_direccion' => $comprobante->direccion_fiscal ?? '',

            'cliente_email' => $order->user->email,

            /*
            |--------------------------------------------------------------------------
            | Totales
            |--------------------------------------------------------------------------
            */

            'porcentaje_de_igv' => 18,

            'total_gravada' => round((float)$order->subtotal, 2),

            'total_igv' => round((float)$order->igv, 2),

            'total' => round((float)$order->total_price, 2),

            /*
            |--------------------------------------------------------------------------
            | Items
            |--------------------------------------------------------------------------
            */

            'items' => $this->items($comprobante),

            /*
            |--------------------------------------------------------------------------
            | Opciones
            |--------------------------------------------------------------------------
            */

            'enviar_automaticamente_a_la_sunat' => true,

            'enviar_automaticamente_al_cliente' => false,

        ];
    }

    private function items(Comprobante $comprobante): array
    {
        $items = [];

        foreach ($comprobante->order->items as $item) {

            $valorUnitario = round((float)$item->unit_price, 2);

            $subtotal = round((float)$item->subtotal, 2);

            $igv = round($subtotal * 0.18, 2);

            $total = round($subtotal + $igv, 2);

            $items[] = [

                'unidad_de_medida' => 'NIU',

                'codigo' => (string)$item->product->id,

                'descripcion' => $item->product->name,

                'cantidad' => (float)$item->quantity,

                'valor_unitario' => $valorUnitario,

                'precio_unitario' => round($valorUnitario * 1.18, 2),

                // Para venta gravada
                'tipo_de_igv' => 1,

                'subtotal' => $subtotal,

                'igv' => $igv,

                'total' => $total,

            ];
        }

        return $items;
    }
}
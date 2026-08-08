<?php

declare(strict_types=1);

namespace App\Services\Facturacion\Payload;

use App\Models\Comprobante;
use App\Services\Facturacion\Support\NubeFact;

final class NubeFactPayloadBuilder
{
    public function build(
        Comprobante $comprobante
    ): array {

        return array_merge(

            $this->configuracion(),

            $this->documento($comprobante),

            $this->cliente($comprobante),

            $this->totales($comprobante),

            $this->items($comprobante),

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Configuración
    |--------------------------------------------------------------------------
    */

    private function configuracion(): array
    {
        return [

            'operacion' => NubeFact::GENERAR_COMPROBANTE,

            'enviar_automaticamente_a_la_sunat' => true,

            'enviar_automaticamente_al_cliente' => false,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Documento
    |--------------------------------------------------------------------------
    */

    private function documento(
        Comprobante $comprobante
    ): array {

        return [

            'tipo_de_comprobante' => $this->tipoComprobante($comprobante),

            'sunat_transaction' => NubeFact::VENTA_INTERNA,

            'fecha_de_emision' => now()->format('d-m-Y'),

            'moneda' => NubeFact::SOLES,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Cliente
    |--------------------------------------------------------------------------
    */

    private function cliente(
        Comprobante $comprobante
    ): array {

        return [

            'cliente_tipo_de_documento' => $this->tipoDocumento($comprobante),

            'cliente_numero_de_documento' => $comprobante->numero_documento,

            'cliente_denominacion' => $comprobante->denominacion(),

            'cliente_direccion' => $comprobante->direccion_fiscal,

            'cliente_email' => $comprobante->order->user->email,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Totales
    |--------------------------------------------------------------------------
    */

    private function totales(
        Comprobante $comprobante
    ): array {

        $order = $comprobante->order;

        return [

            'porcentaje_de_igv' => NubeFact::PORCENTAJE_IGV,

            'total_gravada' => $order->subtotal,

            'total_igv' => $order->igv,

            'total' => $order->total_price,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Items
    |--------------------------------------------------------------------------
    */

    private function items(
        Comprobante $comprobante
    ): array {

        $items = [];

        foreach ($comprobante->order->items as $item) {

            $subtotal = (float) $item->subtotal;

            $igv = round(
                $subtotal * (NubeFact::PORCENTAJE_IGV / 100),
                2
            );

            $items[] = [

                'unidad_de_medida' => NubeFact::UNIDAD,

                'codigo' => (string) $item->product->id,

                'descripcion' => $item->product->name,

                'cantidad' => $item->quantity,

                'valor_unitario' => (float) $item->unit_price,

                'precio_unitario' => round(
                    (float) $item->unit_price * NubeFact::FACTOR_IGV,
                    2
                ),

                'subtotal' => $subtotal,

                'tipo_de_igv' => NubeFact::GRAVADO,

                'igv' => $igv,

                'total' => round(
                    $subtotal + $igv,
                    2
                ),

            ];
        }

        return [

            'items' => $items,

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    private function tipoComprobante(
        Comprobante $comprobante
    ): int {

        return $comprobante->esFactura()
            ? NubeFact::FACTURA
            : NubeFact::BOLETA;
    }

    private function tipoDocumento(
        Comprobante $comprobante
    ): int {

        return $comprobante->usaRuc()
            ? NubeFact::RUC
            : NubeFact::DNI;
    }
}
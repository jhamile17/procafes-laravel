<?php

namespace App\Services;

use App\Models\AlertaStock;
use App\Models\MovimientoInventario;
use App\Models\NivelAlertaStock;
use App\Models\Product;
use App\Models\TipoMovimientoInventario;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class InventoryService
{
    /**
     * Registrar una entrada de inventario.
     */
    public function registrarEntrada(
        Product $product,
        int $cantidad,
        ?User $usuario = null,
        ?string $motivo = null
    ): void {

        DB::transaction(function () use ($product, $cantidad, $usuario, $motivo) {

            $this->registrarMovimiento(
                $product,
                'ENTRY',
                $cantidad,
                $usuario,
                $motivo
            );

        });

    }

    /**
     * Registrar una salida de inventario.
     */
    public function registrarSalida(
        Product $product,
        int $cantidad,
        ?User $usuario = null,
        ?string $motivo = null
    ): void {

        DB::transaction(function () use ($product, $cantidad, $usuario, $motivo) {

            $this->registrarMovimiento(
                $product,
                'EXIT',
                -$cantidad,
                $usuario,
                $motivo
            );

        });

    }
}
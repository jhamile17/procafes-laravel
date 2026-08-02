<?php

declare(strict_types=1);

namespace App\Services\Pagos;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodService
{
    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    private const PAGO_TIENDA = 1;

    private const MERCADO_PAGO = 7;

    /*
    |--------------------------------------------------------------------------
    | Obtener métodos activos
    |--------------------------------------------------------------------------
    */

    public function obtenerActivos(): Collection
    {
        return PaymentMethod::query()
            ->activos()
            ->orderBy('id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener método de pago
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $paymentMethodId
    ): PaymentMethod {

        return PaymentMethod::query()
            ->activos()
            ->findOrFail($paymentMethodId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener Pago en tienda
    |--------------------------------------------------------------------------
    */

    public function obtenerPagoEnTienda(): PaymentMethod
    {
        return $this->obtener(
            self::PAGO_TIENDA
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function obtenerMercadoPago(): PaymentMethod
    {
        return $this->obtener(
            self::MERCADO_PAGO
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar Pago en tienda
    |--------------------------------------------------------------------------
    */

    public function esPagoEnTienda(
        PaymentMethod $method
    ): bool {

        return $method->id === self::PAGO_TIENDA;

    }

    /*
    |--------------------------------------------------------------------------
    | Verificar Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function esMercadoPago(
        PaymentMethod $method
    ): bool {

        return $method->id === self::MERCADO_PAGO;

    }
}
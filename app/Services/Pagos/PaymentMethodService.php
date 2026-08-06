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

    public const STORE = 'store';

    public const MERCADOPAGO = 'mercadopago';

    /*
    |--------------------------------------------------------------------------
    | Obtener métodos activos
    |--------------------------------------------------------------------------
    */

    public function obtenerActivos(): Collection
    {
        return PaymentMethod::query()

            ->activos()

            ->orderBy('nombre')

            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener por ID
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $paymentMethodId
    ): PaymentMethod {

        return PaymentMethod::query()

            ->activos()

            ->findOrFail(
                $paymentMethodId
            );

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener por código
    |--------------------------------------------------------------------------
    */

    public function obtenerPorCodigo(
        string $codigo
    ): PaymentMethod {

        return PaymentMethod::query()

            ->activos()

            ->where(
                'codigo',
                strtolower($codigo)
            )

            ->firstOrFail();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener Pago en tienda
    |--------------------------------------------------------------------------
    */

    public function obtenerPagoEnTienda(): PaymentMethod
    {
        return $this->obtenerPorCodigo(
            self::STORE
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function obtenerMercadoPago(): PaymentMethod
    {
        return $this->obtenerPorCodigo(
            self::MERCADOPAGO
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

        return $method->codigo === PaymentMethod::STORE;

    }

    /*
    |--------------------------------------------------------------------------
    | Verificar Mercado Pago
    |--------------------------------------------------------------------------
    */

    public function esMercadoPago(
        PaymentMethod $method
    ): bool {

        return $method->codigo === PaymentMethod::MERCADOPAGO;

    }
}
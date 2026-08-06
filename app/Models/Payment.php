<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'order_id',

        'payment_method_id',

        'estado_pago_id',

        'amount',

        'transaction_id',

        'reference',

        'transaction_data',

    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones automáticas
    |--------------------------------------------------------------------------
    */

    protected $with = [

        'paymentMethod',

        'estadoPago',

    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'amount' => 'decimal:2',

            'transaction_data' => 'array',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class
        );
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(
            PaymentMethod::class
        );
    }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(
            EstadoPago::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar estado
    |--------------------------------------------------------------------------
    */

    public function actualizarEstado(
        EstadoPago $estado
    ): self {

        $this->update([

            'estado_pago_id' => $estado->id,

        ]);

        return $this->refresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de estado
    |--------------------------------------------------------------------------
    */

    public function isPendiente(): bool
    {
        return $this->estadoPago?->esPendiente() ?? false;
    }

    public function isProcesando(): bool
    {
        return $this->estadoPago?->esProcesando() ?? false;
    }

    public function isPagado(): bool
    {
        return $this->estadoPago?->esPagado() ?? false;
    }

    public function isRechazado(): bool
    {
        return $this->estadoPago?->esRechazado() ?? false;
    }

    public function isCancelado(): bool
    {
        return $this->estadoPago?->esCancelado() ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function tieneTransaccion(): bool
    {
        return ! empty(
            $this->transaction_id
        );
    }

    public function tieneReferencia(): bool
    {
        return ! empty(
            $this->reference
        );
    }

    public function tieneDatosTransaccion(): bool
    {
        return ! empty(
            $this->transaction_data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendientes($query)
    {
        return $query->whereHas(

            'estadoPago',

            fn ($q) => $q->where(

                'codigo',

                EstadoPago::PENDIENTE

            )

        );
    }

    public function scopePagados($query)
    {
        return $query->whereHas(

            'estadoPago',

            fn ($q) => $q->where(

                'codigo',

                EstadoPago::APPROVED

            )

        );
    }
}
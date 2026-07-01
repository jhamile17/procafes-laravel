<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

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
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function estadoPago(): BelongsTo
    {
        return $this->belongsTo(EstadoPago::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function actualizarEstado(EstadoPago $estado): void
    {
        $this->update([
            'estado_pago_id' => $estado->id,
        ]);
    }

    public function isPendiente(): bool
    {
        return $this->estadoPago?->esPendiente() ?? false;
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
        return !empty($this->transaction_id);
    }

    public function tieneReferencia(): bool
    {
        return !empty($this->reference);
    }
}
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

    public function isPendiente(): bool
    {
        return strtoupper($this->estadoPago->codigo) === 'PENDIENTE';
    }

    public function isPagado(): bool
    {
        return strtoupper($this->estadoPago->codigo) === 'PAGADO';
    }

    public function isRechazado(): bool
    {
        return strtoupper($this->estadoPago->codigo) === 'RECHAZADO';
    }
}
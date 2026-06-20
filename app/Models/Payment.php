<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'transaction_id',
        'transaction_json',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order()
    {
        return $this->belongsTo(
            Order::class,
            'order_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public static function statusMap(): array
    {
        return [
            'pending'   => 'Pendiente',
            'completed' => 'Completado',
            'failed'    => 'Fallido',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusMap()[$this->status]
            ?? ucfirst($this->status);
    }
}
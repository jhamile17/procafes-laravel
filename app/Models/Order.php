<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total_price',
        'status',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(
            ShippingAddress::class,
            'shipping_address_id'
        );
    }

    public function items()
    {
        return $this->hasMany(
            OrderItem::class,
            'order_id'
        );
    }

    public function payment()
    {
        return $this->hasOne(
            Payment::class,
            'order_id'
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
            'paid'      => 'Pagado',
            'shipped'   => 'Enviado',
            'cancelled' => 'Cancelado',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusMap()[$this->status]
            ?? ucfirst($this->status);
    }
}
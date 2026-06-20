<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';

    protected $fillable = [
        'orders_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];
        /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
     */

    public function order(){
        return $this->belongsTo(
            Order::class, 
            'order_id',
            'id');
    }

    public function product(){
        return $this->belongsTo(
            Product::class, 
            'product_id',
            'id');
    }
}

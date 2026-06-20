<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $table = 'shipping_addresses';

    protected $fillable = [
        'user_id',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
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

    public function orders()
    {
        return $this->hasMany(
            Order::class,
            'shipping_address_id'
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'price',
        'sub_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'price' => 'decimal:2',
            'sub_total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function refreshSubtotal(): void
    {
        $this->sub_total = bcmul(
            (string) $this->price,
            (string) $this->quantity,
            2
        );
    }
}
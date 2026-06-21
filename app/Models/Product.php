<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'stock',
        'stock_minimo',
        'status',
        'categories_id',
        'brand_id',
        'image',
        'description',
    ];

    protected $appends = [
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'stock' => 'integer',
            'stock_minimo' => 'integer',
            'status' => 'boolean',
            'categories_id' => 'integer',
            'brand_id' => 'integer',
        ];
    }

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image || !Storage::disk('public')->exists($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'categories_id',
            'categories_id'
        );
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(
            Brand::class,
            'brand_id',
            'brand_id'
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function stockAlerts(): HasMany
    {
        return $this->hasMany(AlertasStock::class, 'product_id');
    }

    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function isAvailable(int $quantity = 1): bool
    {
        return $this->status && $quantity > 0 && $this->stock >= $quantity;
    }
}
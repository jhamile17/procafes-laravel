<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\AlertasStock;
use App\Services\AlertasStockService;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'price',
        'stock',
        'stock_minimo',
        'status',
        'categories_id',
        'brand_id',
        'image',
        'description'
    ];

    protected $appends = ['image_url'];

    protected $casts = [
        'price' => 'float',
        'stock' => 'integer',
        'stock_minimo' => 'integer',
        'status' => 'boolean',
        'categories_id' => 'integer',
        'brand_id' => 'integer',
    ];

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function category() {
        return $this->belongsTo(
            Category::class, 'categories_id', 'categories_id');
    }

    public function brand() {
        return $this->belongsTo(
            Brand::class, 'brand_id', 'brand_id');
    }

    public function reviews()
    {
        return $this->hasMany(
            Review::class, 'products_id', 'id');
    }

    public function wishlists()
    {
        return $this->hasMany(
            Wishlist::class, 'product_id', 'id');
    }
    public function orderItems()
    {
        return $this->hasMany(
            OrderItem::class, 'product_id', 'id');
    }

    // Observer para cambios en stock
    protected static function booted()
    {
        static::updated(function ($product) {
            // Si cambió el stock
            if ($product->isDirty('stock')) {
                // Revisar y disparar alerta + notificación
                (new AlertasStockService())->revisarStock($product);
            }
        });
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    /**
     * Propiedad temporal para registrar
     * el motivo del cambio de precio.
     */
    public ?string $motivo_cambio = null;

    protected $fillable = [

        'categories_id',
        'brand_id',
        'tipo_consumo_id',

        'sku',
        'barcode',

        'name',
        'slug',
        'description',

        'cost_price',
        'sale_price',

        'stock',
        'stock_minimo',

        'image',

        'status',

    ];

    protected $appends = [
        'image_url',
    ];

    protected function casts(): array
    {
        return [

            'cost_price' => 'decimal:2',

            'sale_price' => 'decimal:2',

            'stock' => 'integer',

            'stock_minimo' => 'integer',

            'status' => 'boolean',

            'categories_id' => 'integer',

            'brand_id' => 'integer',

            'tipo_consumo_id' => 'integer',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (!Storage::disk('public')->exists($this->image)) {
            return null;
        }

        return Storage::disk('public')->url($this->image);
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categories_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function tipoConsumo(): BelongsTo
    {
        return $this->belongsTo(TipoConsumo::class, 'tipo_consumo_id');
    }

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    public function historialPrecios(): HasMany
    {
        return $this->hasMany(HistorialPrecio::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function movimientosInventario(): HasMany
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function alertasStock(): HasMany
    {
        return $this->hasMany(AlertaStock::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isLowStock(): bool
    {
        return $this->stock <= $this->stock_minimo;
    }

    public function isAvailable(int $quantity = 1): bool
    {
        return $this->status
            && $quantity > 0
            && $this->stock >= $quantity;
    }
}
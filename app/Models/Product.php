<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Brand;
use App\Models\TipoConsumo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    public const ACTIVO = true;

    public const INACTIVO = false;

    public const STOCK_MINIMO_DEFAULT = 5;
        /*
    |--------------------------------------------------------------------------
    | Tipos de entrega
    |--------------------------------------------------------------------------
    */

    public const ENTREGA_AMBOS = 'AMBOS';

    public const ENTREGA_RECOJO = 'RECOJO';

    /*
    |--------------------------------------------------------------------------
    | Propiedades temporales
    |--------------------------------------------------------------------------
    */

    /**
     * Motivo del cambio de precio.
     * Se utiliza desde el Observer cuando cambia el precio.
     */
    public ?string $motivo_cambio = null;

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

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
        'delivery_type',

    ];

    /*
    |--------------------------------------------------------------------------
    | Atributos calculados
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'image_url',

        'precio_formateado',

        'stock_status',

        'stock_badge',

        'can_add_to_cart',

        'cart_button_text',

        'cart_button_icon',

    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

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
            'delivery_type' => 'string',

        ];
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

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
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
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos($query)
    {
        return $query->where('status', true);
    }

    public function scopeInactivos($query)
    {
        return $query->where('status', false);
    }

    public function scopeConStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeDisponibles($query)
    {
        return $query
            ->where('status', true)
            ->where('stock', '>', 0);
    }

    public function scopeStockBajo($query)
    {
        return $query->whereColumn(
            'stock',
            '<=',
            'stock_minimo'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status;
    }

    public function activar(): void
    {
        $this->update([
            'status' => self::ACTIVO,
        ]);
    }

    public function desactivar(): void
    {
        $this->update([
            'status' => self::INACTIVO,
        ]);
    }

    public function hasStock(): bool
    {
        return $this->stock > 0;
    }

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

    public function incrementarStock(int $cantidad): void
    {
        $this->increment('stock', $cantidad);
    }

    public function disminuirStock(int $cantidad): void
    {
        $this->decrement('stock', $cantidad);
    }
    /*tipos de entrega */
    public function soloRecojo(): bool
    {
        return $this->delivery_type === self::ENTREGA_RECOJO;
    }

    public function permiteEnvio(): bool
    {
        return $this->delivery_type === self::ENTREGA_AMBOS;
    }
    /*
    |--------------------------------------------------------------------------
    | Accesores y Mutadores
    |--------------------------------------------------------------------------
    */
    public function getCanAddToCartAttribute(): bool
    {
        return $this->isAvailable();
    }

    public function getCartButtonTextAttribute(): string
    {
        return $this->isAvailable()
            ? 'Agregar al carrito'
            : 'Sin stock';
    }

    public function getCartButtonIconAttribute(): string
    {
        return $this->isAvailable()
            ? 'bi-cart-plus'
            : 'bi-x-circle';
    }
    public function getImageUrlAttribute(): string
    {
        if (!filled($this->image)) {
            return asset('images/no-image.png');
        }

        $path = 'storage/' . ltrim($this->image, '/');

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return asset('images/no-image.png');
    }

    public function getPrecioFormateadoAttribute(): string
    {
        return 'S/ ' . number_format($this->sale_price, 2);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'Agotado';

        if ($this->stock <= $this->stock_minimo) return 'Stock Bajo';

        return 'Disponible';
    }

    public function getStockBadgeAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'danger';
        }

        if ($this->stock <= $this->stock_minimo) {
            return 'warning';
        }

        return 'success';
    }

}
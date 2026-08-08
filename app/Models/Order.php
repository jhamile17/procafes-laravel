<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'estado_pedido_id',
        'numero_pedido',
        'delivery_alias',
        'delivery_direccion',
        'delivery_numero',
        'delivery_departamento',
        'delivery_provincia',
        'delivery_distrito',
        'delivery_referencia',
        'subtotal',
        'igv',
        'total_price',
        'delivery_type',
        'observaciones',

    ];

    /*Relaciones automáticas*/

    protected $with = [
        'estadoPedido',
        'payment',
    ];

    /*Conversión de atributos*/

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'igv' => 'decimal:2',
            'total_price' => 'decimal:2',
        ];
    }

    /*Relaciones*/

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(
            ShippingAddress::class
        );
    }
    public function estadoPedido(): BelongsTo
    {
        return $this->belongsTo(
            EstadoPedido::class
        );
    }

    public function items(): HasMany
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(
            Payment::class
        );
    }

    public function electronicDocument(): HasOne
    {
        return $this->hasOne(
            ElectronicDocument::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de estado
    |--------------------------------------------------------------------------
    */

    public function esPendiente(): bool
    {
        return $this->estadoPedido?->esPendiente() ?? false;
    }

    public function esConfirmado(): bool
    {
        return $this->estadoPedido?->esConfirmado() ?? false;
    }

    public function esCancelado(): bool
    {
        return $this->estadoPedido?->esCancelado() ?? false;
    }

    public function esEntregado(): bool
    {
        return $this->estadoPedido?->esEntregado() ?? false;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function tienePago(): bool
    {
        return $this->payment()->exists();
    }

    public function tieneDocumentoElectronico(): bool
    {
        return $this->electronicDocument()->exists();
    }

    public function calcularTotal(): float
    {
        return (float) $this->items()
            ->sum('subtotal');
    }

    public function totalItems(): int
    {
        return (int) $this->items()
            ->sum('quantity');
    }

    public function actualizarTotal(): self
    {
        $this->update([

            'total_price' => $this->calcularTotal(),

        ]);

        return $this->refresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePendientes($query)
    {
        return $query->whereHas(

            'estadoPedido',

            fn ($q) => $q->where(

                'codigo',

                EstadoPedido::PENDIENTE

            )

        );
    }

    public function scopeConfirmados($query)
    {
        return $query->whereHas(

            'estadoPedido',

            fn ($q) => $q->where(

                'codigo',

                EstadoPedido::CONFIRMADO

            )

        );
    }

    public function scopeEntregados($query)
    {
        return $query->whereHas(

            'estadoPedido',

            fn ($q) => $q->where(

                'codigo',

                EstadoPedido::ENTREGADO

            )

        );
    }
}
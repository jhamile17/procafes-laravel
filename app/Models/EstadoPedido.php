<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoPedido extends Model
{
    use HasFactory;

    protected $table = 'estados_pedido';

    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    public const PENDIENTE     = 'PENDIENTE';
    public const CONFIRMADO    = 'CONFIRMADO';
    public const PREPARACION   = 'PREPARACION';
    public const EN_CAMINO     = 'EN_CAMINO';
    public const LISTO_RECOJO  = 'LISTO_RECOJO';
    public const ENTREGADO     = 'ENTREGADO';
    public const CANCELADO     = 'CANCELADO';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
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

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status;
    }

    public function esPendiente(): bool
    {
        return $this->codigo === self::PENDIENTE;
    }

    public function esConfirmado(): bool
    {
        return $this->codigo === self::CONFIRMADO;
    }

    public function esPreparacion(): bool
    {
        return $this->codigo === self::PREPARACION;
    }

    public function esEnCamino(): bool
    {
        return $this->codigo === self::EN_CAMINO;
    }

    public function esListoRecojo(): bool
    {
        return $this->codigo === self::LISTO_RECOJO;
    }

    public function esEntregado(): bool
    {
        return $this->codigo === self::ENTREGADO;
    }

    public function esCancelado(): bool
    {
        return $this->codigo === self::CANCELADO;
    }

    /*
    |--------------------------------------------------------------------------
    | Badge
    |--------------------------------------------------------------------------
    */

    public function getBadgeClass(): string
    {
        return match ($this->codigo) {
            self::PENDIENTE    => 'pending',
            self::CONFIRMADO   => 'processing',
            self::PREPARACION  => 'processing',
            self::EN_CAMINO    => 'processing',
            self::LISTO_RECOJO => 'processing',
            self::ENTREGADO   => 'completed',
            self::CANCELADO   => 'cancelled',
            default            => 'default',
        };
    }
}
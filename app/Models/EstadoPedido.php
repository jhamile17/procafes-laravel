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

    public const PENDIENTE  = 'PENDIENTE';
    public const CONFIRMADO = 'CONFIRMADO';
    public const CANCELADO  = 'CANCELADO';
    public const ENTREGADO  = 'ENTREGADO';

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
    | Conversión de atributos
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
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->status;
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers de estado
    |--------------------------------------------------------------------------
    */

    public function esPendiente(): bool
    {
        return $this->codigo === self::PENDIENTE;
    }

    public function esConfirmado(): bool
    {
        return $this->codigo === self::CONFIRMADO;
    }

    public function esCancelado(): bool
    {
        return $this->codigo === self::CANCELADO;
    }

    public function esEntregado(): bool
    {
        return $this->codigo === self::ENTREGADO;
    }
        /**
     * Obtener la clase CSS del estado.
     */
    public function getBadgeClass(): string
    {
        return match ($this->codigo) {
            self::PENDIENTE  => 'pending',
            self::CONFIRMADO => 'processing',
            self::ENTREGADO  => 'completed',
            self::CANCELADO  => 'cancelled',
            default          => 'default',
        };
    }
}
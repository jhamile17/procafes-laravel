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
        return strtoupper($this->codigo) === 'PENDIENTE';
    }

    public function esConfirmado(): bool
    {
        return strtoupper($this->codigo) === 'CONFIRMADO';
    }

    public function esCancelado(): bool
    {
        return strtoupper($this->codigo) === 'CANCELADO';
    }

    public function esEntregado(): bool
    {
        return strtoupper($this->codigo) === 'ENTREGADO';
    }
}
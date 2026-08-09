<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    public const STORE = 'store';

    public const MERCADOPAGO = 'mercadopago';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'estado',
    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isActive(): bool
    {
        return $this->estado;
    }

    public function esPagoEnTienda(): bool
    {
        return $this->codigo === self::STORE;
    }

    public function esMercadoPago(): bool
    {
        return $this->codigo === self::MERCADOPAGO;
    }
}
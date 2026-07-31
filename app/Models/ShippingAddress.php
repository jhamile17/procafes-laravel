<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingAddress extends Model
{
    use HasFactory;

    protected $table = 'shipping_addresses';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'user_id',
        'alias',
        'direccion',
        'departamento',
        'provincia',
        'distrito',
        'referencia',
        'latitude',
        'longitude',
        'es_principal',
    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
            'es_principal' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePrincipal($query)
    {
        return $query->where('es_principal', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function isPrincipal(): bool
    {
        return $this->es_principal;
    }

    /**
     * Obtiene la dirección completa.
     */
    public function direccionCompleta(): string
    {
        return collect([
            $this->direccion,
            $this->distrito,
            $this->provincia,
            $this->departamento,
        ])
        ->filter()
        ->implode(', ');
    }

    /**
     * Obtiene el alias o un valor por defecto.
     */
    public function nombre(): string
    {
        return $this->alias ?: 'Mi dirección';
    }
}
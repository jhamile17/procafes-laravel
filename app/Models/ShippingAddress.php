<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingAddress extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    public const PRINCIPAL = true;

    /*
    |--------------------------------------------------------------------------
    | Tabla
    |--------------------------------------------------------------------------
    */

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
        'numero',
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
    | Atributos calculados
    |--------------------------------------------------------------------------
    */

    protected $appends = [

        'direccion_completa',

        'nombre',

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

    public function scopePrincipal(
        Builder $query
    ): Builder {

        return $query->where(
            'es_principal',
            self::PRINCIPAL
        );

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

    /*
    |--------------------------------------------------------------------------
    | Accesores
    |--------------------------------------------------------------------------
    */

    public function getDireccionCompletaAttribute(): string
    {
        return collect([

            trim($this->direccion .' '.$this->numero),

            $this->distrito,

            $this->provincia,

            $this->departamento,

        ])

        ->filter()

        ->implode(', ');
    }

    public function getNombreAttribute(): string
    {
        return filled($this->alias)

            ? $this->alias

            : 'Mi dirección';
    }
}
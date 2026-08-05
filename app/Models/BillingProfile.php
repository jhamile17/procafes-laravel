<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BillingProfile extends Model
{
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | Estados
    |--------------------------------------------------------------------------
    */

    public const ACTIVO = true;

    public const INACTIVO = false;

    /*
    |--------------------------------------------------------------------------
    | Predeterminado
    |--------------------------------------------------------------------------
    */

    public const PREDETERMINADO = true;

    public const NO_PREDETERMINADO = false;

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'user_id',

        'alias',

        'ruc',

        'razon_social',

        'direccion_fiscal',

        'predeterminado',

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

            'user_id' => 'integer',

            'predeterminado' => 'boolean',

            'estado' => 'boolean',

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

    public function electronicDocuments(): HasMany
    {
        return $this->hasMany(ElectronicDocument::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos(Builder $query): Builder
    {
        return $query->where(
            'estado',
            self::ACTIVO
        );
    }

    public function scopeDelUsuario(
        Builder $query,
        int $userId
    ): Builder {

        return $query->where(
            'user_id',
            $userId
        );
    }

    public function scopePredeterminados(
        Builder $query
    ): Builder {

        return $query->where(
            'predeterminado',
            self::PREDETERMINADO
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function activar(): void
    {
        $this->update([
            'estado' => self::ACTIVO,
        ]);
    }

    public function desactivar(): void
    {
        $this->update([
            'estado' => self::INACTIVO,
        ]);
    }

    public function marcarComoPredeterminado(): void
    {
        static::where(
            'user_id',
            $this->user_id
        )->update([
            'predeterminado' => self::NO_PREDETERMINADO,
        ]);

        $this->update([
            'predeterminado' => self::PREDETERMINADO,
        ]);
    }

    public function esPredeterminado(): bool
    {
        return $this->predeterminado;
    }

    public function estaActivo(): bool
    {
        return $this->estado;
    }
}
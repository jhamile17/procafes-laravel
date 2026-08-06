<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoPago extends Model
{
    use HasFactory;

    protected $table = 'estados_pago';

    /*
    |--------------------------------------------------------------------------
    | Constantes
    |--------------------------------------------------------------------------
    */

    public const PENDING = 'PENDING';

    public const PROCESSING = 'PROCESSING';

    public const APPROVED = 'APPROVED';

    public const REJECTED = 'REJECTED';

    public const REFUNDED = 'REFUNDED';

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
        return $query->where(
            'status',
            true
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

    /*
    |--------------------------------------------------------------------------
    | Helpers de estado
    |--------------------------------------------------------------------------
    */

    public function esPendiente(): bool
    {
        return $this->codigo === self::PENDING;
    }

    public function esProcesando(): bool
    {
        return $this->codigo === self::PROCESSING;
    }

    public function esAprobado(): bool
    {
        return $this->codigo === self::APPROVED;
    }

    public function esRechazado(): bool
    {
        return $this->codigo === self::REJECTED;
    }

    public function esReembolsado(): bool
    {
        return $this->codigo === self::REFUNDED;
    }

    /*
    |--------------------------------------------------------------------------
    | Clase CSS para badges
    |--------------------------------------------------------------------------
    */

    public function getBadgeClass(): string
    {
        return match ($this->codigo) {

            self::PENDING => 'pending',

            self::PROCESSING => 'processing',

            self::APPROVED => 'completed',

            self::REJECTED => 'cancelled',

            self::REFUNDED => 'refunded',

            default => 'default',

        };
    }
}
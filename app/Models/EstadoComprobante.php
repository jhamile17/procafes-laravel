<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EstadoComprobante extends Model
{

    use HasFactory;
    protected $table = 'estados_comprobante';
    /*
    |--------------------------------------------------------------------------
    | Estados
    |--------------------------------------------------------------------------
    */

    public const PENDIENTE = 'PENDIENTE';

    public const EMITIDO = 'EMITIDO';

    public const ANULADO = 'ANULADO';

    /*
    |--------------------------------------------------------------------------
    | Configuración
    |--------------------------------------------------------------------------
    */

    public const ACTIVO = true;

    public const INACTIVO = false;

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

    public function comprobantes(): HasMany
    {
        return $this->hasMany(
            Comprobante::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActivos(
        Builder $query
    ): Builder {

        return $query->where(
            'estado',
            self::ACTIVO
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function esPendiente(): bool
    {
        return $this->codigo === self::PENDIENTE;
    }

    public function esEmitido(): bool
    {
        return $this->codigo === self::EMITIDO;
    }

    public function esAnulado(): bool
    {
        return $this->codigo === self::ANULADO;
    }

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
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HorarioEmpresa extends Model
{
    use HasFactory;

    protected $table = 'horarios_empresa';

    protected $fillable = [
        'configuracion_empresa_id',
        'dia',
        'hora_apertura',
        'hora_cierre',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relación con configuración de empresa
    |--------------------------------------------------------------------------
    */

    public function configuracion(): BelongsTo
    {
        return $this->belongsTo(
            ConfiguracionEmpresa::class,
            'configuracion_empresa_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Estado
    |--------------------------------------------------------------------------
    */

    public function estaActivo(): bool
    {
        return $this->activo;
    }
}
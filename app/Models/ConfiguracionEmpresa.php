<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConfiguracionEmpresa extends Model
{
    use HasFactory;

    protected $table = 'configuracion_empresa';

    protected $fillable = [
        'nombre_empresa',
        'ruc',
        'correo',
        'telefono',
        'direccion',
        'logo',
        'facebook',
        'instagram',
        'tiktok',
        'whatsapp',
        'hora_apertura',
        'hora_cierre',
    ];

    /**
     * Obtiene la configuración principal de la empresa.
     */
    public static function obtener(): ?self
    {
        return static::query()->first();
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIÓN CON HORARIOS
    |--------------------------------------------------------------------------
    */

    public function horarios(): HasMany
    {
        return $this->hasMany(
            HorarioEmpresa::class,
            'configuracion_empresa_id'
        );
    }
}
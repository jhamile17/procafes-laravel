<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    /**
     * Obtiene la configuración principal de la empresa.
     */
    public static function obtener(): ?self
    {
        return static::query()->first();
    }
}
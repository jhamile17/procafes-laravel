<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PendingRegistration extends Model
{
    protected $fillable = [

        'nombres',
        'apellido_paterno',
        'apellido_materno',

        'tipo_documento',
        'numero_documento',

        'telefono',

        'email',

        'password',

        'token',

        'expires_at',

    ];

    protected $casts = [

        'expires_at' => 'datetime',

    ];

    /*
    |--------------------------------------------------------------------------
    | Verificar expiración
    |--------------------------------------------------------------------------
    */

    public function expirado(): bool
    {
        return $this->expires_at->isPast();
    }

    /*
    |--------------------------------------------------------------------------
    | Crear token
    |--------------------------------------------------------------------------
    */

    public static function generarToken(): string
    {
        return (string) \Illuminate\Support\Str::uuid();
    }

    /*
    |--------------------------------------------------------------------------
    | Fecha de expiración
    |--------------------------------------------------------------------------
    */

    public static function fechaExpiracion(): Carbon
    {
        return now()->addHours(24);
    }
}
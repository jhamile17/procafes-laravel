<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaStock extends Model
{
    use HasFactory;

    protected $table = 'alertas_stock';

    /*
    |--------------------------------------------------------------------------
    | Asignación masiva
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'product_id',
        'nivel_alerta_id',
        'stock_detectado',
        'mensaje',
        'enviado_correo',
        'enviado_app',
        'fecha_envio',
    ];

    /*
    |--------------------------------------------------------------------------
    | Conversión de atributos
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'enviado_correo' => 'boolean',
            'enviado_app' => 'boolean',
            'fecha_envio' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function nivelAlerta(): BelongsTo
    {
        return $this->belongsTo(NivelAlertaStock::class, 'nivel_alerta_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public function fueEnviada(): bool
    {
        return $this->enviado_correo || $this->enviado_app;
    }

    public function pendienteEnvio(): bool
    {
        return ! $this->fueEnviada();
    }
}
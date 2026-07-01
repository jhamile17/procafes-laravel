<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoConsumo extends Model
{
    protected $table = 'tipos_consumo';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'tipo_consumo_id');
    }
}
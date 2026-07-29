<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElectronicDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'tipo',
        'serie',
        'numero',
        'estado',
        'pdf_url',
        'xml_url',
        'cdr_url',
        'fecha_emision',
    ];

    protected function casts(): array
    {
        return [
            'fecha_emision' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
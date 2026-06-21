<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';

    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'transaction_id',
        'transaction_json',
        'status',
    ];

    protected $appends = [
        'status_label',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transaction_json' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public static function statusMap(): array
    {
        return [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_COMPLETED => 'Completado',
            self::STATUS_FAILED => 'Fallido',
        ];
    }

    public function getStatusLabelAttribute(): string
    {
        return self::statusMap()[$this->status] ?? ucfirst((string) $this->status);
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }
}
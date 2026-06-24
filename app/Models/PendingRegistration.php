<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PendingRegistration extends Model
{
    use Notifiable;
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'token',
        'expires_at',
    ];
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }
}

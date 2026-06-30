<?php

namespace App\Models;

use App\Notifications\ResetPasswordProcafes;
use App\Notifications\WelcomeVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    //asignacion masiva
        protected $fillable = [
        'role_id',
        'name',
        'nombres',
        'apellido_paterno',
        'apellido_materno',
        'tipo_documento',
        'numero_documento',
        'email',
        'password',
        'provider',
        'provider_id',
        'celular',
        'direccion',
        'foto_perfil',
        'estado',
        'ultimo_acceso',
    ];
    //atributos ocultos
    protected $hidden = [
        'password',
        'remember_token',
    ];
    //conversion de atributos
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ultimo_acceso' => 'datetime',
            'estado' => 'boolean',
            'password' => 'hashed',
        ];
    }
    //relaciones
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }
    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }
    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class);
    }
    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    //notificaciones
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new WelcomeVerifyEmail());
    }
     public function sendPasswordResetNotification(string $token): void
    {
        $this->notify(new ResetPasswordProcafes($token));
    }

    //metodos auxiliares
    public function isAdmin(): bool
    {
        return $this->role?->codigo === 'ADMIN';
    }

    public function isCustomer(): bool
    {
        return $this->role?->codigo === 'CUSTOMER';
    }
    public function isActive(): bool
    {
        return $this->estado;
    }
    //cambios para nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return trim(
            "{$this->nombres} {$this->apellido_paterno} {$this->apellido_materno}"
        );
    }
}
<?php

namespace App\Models;
use App\Notifications\ResetPasswordProcafes;
use App\Notifications\WelcomeVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    
    public const PROVIDER_LOCAL = 'local';
    public const PROVIDER_GOOGLE = 'google';

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
        'has_local_password',
        'provider',
        'provider_id',
        'celular',
        'foto_perfil',
        'estado',
        'ultimo_acceso',
        'email_verified_at'
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
            'has_local_password'=>'boolean',
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
        public function primaryShippingAddress(): HasOne
    {
        return $this->hasOne(ShippingAddress::class)
            ->where('es_principal', true);
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
    public function sendPasswordResetNotification($token)
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
        /*
    |--------------------------------------------------------------------------
    | Construir nombre completo
    |--------------------------------------------------------------------------
    */

    public static function construirNombreCompleto(
        string $nombres,
        string $apellidoPaterno,
        ?string $apellidoMaterno = null
    ): string {

        return trim(
            implode(' ', array_filter([
                trim($nombres),
                trim($apellidoPaterno),
                $apellidoMaterno ? trim($apellidoMaterno) : null,
            ]))
        );

    }
    //cambios para nombre completo
    public function getNombreCompletoAttribute(): string
    {
        return self::construirNombreCompleto(
            $this->nombres,
            $this->apellido_paterno,
            $this->apellido_materno
        );
    }
    /**
     * Obtiene la URL de la foto de perfil.
     */
   public function getFotoPerfilUrlAttribute(): string
    {
        if (empty($this->foto_perfil)) {
            return asset('images/default-avatar.png');
        }

        // Avatar externo de Google
        if (filter_var($this->foto_perfil, FILTER_VALIDATE_URL)) {
            return $this->foto_perfil;
        }

        // Foto subida manualmente
        return Storage::disk('public')->url(
            $this->foto_perfil
        );
    }
}
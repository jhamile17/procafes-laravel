<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserRegistrationService
{
    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */

    public function register(array $data): User
    {
        return DB::transaction(function () use ($data) {

            $role = Role::query()
                ->where('codigo', 'CUSTOMER')
                ->firstOrFail();

            $nombreCompleto = $data['name']
                ?? User::construirNombreCompleto(
                    $data['nombres'] ?? '',
                    $data['apellido_paterno'] ?? '',
                    $data['apellido_materno'] ?? ''
                );

            $user = User::create([

                'role_id' => $data['role_id'] ?? $role->id,

                'name' => $nombreCompleto,

                'nombres' => $data['nombres']
                    ?? $nombreCompleto,

                'apellido_paterno' => $data['apellido_paterno']
                    ?? '',

                'apellido_materno' => $data['apellido_materno']
                    ?? '',

                'tipo_documento' => $data['tipo_documento']
                    ?? null,

                'numero_documento' => $data['numero_documento']
                    ?? null,

                'email' => strtolower(
                    trim($data['email'])
                ),

                /*
                |--------------------------------------------------------------------------
                | Si el proveedor no tiene contraseña
                | generamos una aleatoria.
                |--------------------------------------------------------------------------
                */

                'password' => $data['password']
                    ?? Str::random(60),

                'provider' => $data['provider']
                    ?? User::PROVIDER_LOCAL,

                'provider_id' => $data['provider_id']
                    ?? null,

                'celular' => $data['celular']
                    ?? null,

                'direccion' => $data['direccion']
                    ?? null,

                'foto_perfil' => $data['foto_perfil']
                    ?? null,

                'estado' => true,

                'ultimo_acceso' => now(),

                'email_verified_at' => $data['email_verified_at']
                    ?? null,

            ]);

            if (
                $user->provider === User::PROVIDER_LOCAL
                && ! $user->hasVerifiedEmail()
            ) {
                $user->sendEmailVerificationNotification();
            }

            return $user;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Vincular proveedor OAuth
    |--------------------------------------------------------------------------
    */

    public function linkProvider(
        User $user,
        string $provider,
        string $providerId
    ): User {

        $user->update([

            'provider' => $provider,

            'provider_id' => $providerId,

            'email_verified_at' => $user->hasVerifiedEmail()
                ? $user->email_verified_at
                : now(),

        ]);

        return $user->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar correo
    |--------------------------------------------------------------------------
    */

    public function verifyEmail(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $user->markEmailAsVerified();

        event(new Verified($user));
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar último acceso
    |--------------------------------------------------------------------------
    */

    public function updateLastAccess(User $user): void
    {
        $user->update([
            'ultimo_acceso' => now(),
        ]);
    }
}
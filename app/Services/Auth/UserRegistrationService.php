<?php

namespace App\Services\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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

            $customerRole = Role::customer();

            $user = User::create([

                'role_id' => $customerRole->id,

                'name' => User::construirNombreCompleto(
                    $data['nombres'],
                    $data['apellido_paterno'],
                    $data['apellido_materno'] ?? null,
                ),

                'nombres' => trim($data['nombres']),

                'apellido_paterno' => trim($data['apellido_paterno']),

                'apellido_materno' => trim(
                    $data['apellido_materno'] ?? ''
                ),

                'tipo_documento' => $data['tipo_documento'] ?? null,

                'numero_documento' => $data['numero_documento'] ?? null,

                'email' => strtolower(trim($data['email'])),

                'password' => $data['password'],

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

                'ultimo_acceso' => null,

                'email_verified_at' => $data['email_verified_at']
                    ?? null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Solo los registros locales requieren
            | verificar el correo.
            |--------------------------------------------------------------------------
            */

            if (

                $user->provider === User::PROVIDER_LOCAL

                &&

                ! $user->hasVerifiedEmail()

            ) {

                $user->sendEmailVerificationNotification();

            }

            return $user;

        });
    }
}
<?php

namespace App\Services\Auth;

use App\Models\PendingRegistration;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\VerifyPendingRegistration;
use Illuminate\Support\Facades\DB;


class PendingRegistrationService
{
    /*
    |--------------------------------------------------------------------------
    | Registrar solicitud
    |--------------------------------------------------------------------------
    */

    public function registrar(array $data): PendingRegistration
    {
        return DB::transaction(function () use ($data) {

            PendingRegistration::query()
                ->where('email', strtolower(trim($data['email'])))
                ->delete();

            $pending = PendingRegistration::create([

                'nombres' => $data['nombres'],

                'apellido_paterno' => $data['apellido_paterno'],

                'apellido_materno' => $data['apellido_materno'],

                'tipo_documento' => $data['tipo_documento'],

                'numero_documento' => $data['numero_documento'],

                'telefono' => $data['telefono'] ?? null,

                'email' => strtolower(
                    trim($data['email'])
                ),

                'password' => Crypt::encryptString(
                    $data['password']
                ),

                'token' => PendingRegistration::generarToken(),

                'expires_at' => PendingRegistration::fechaExpiracion(),

            ]);

            /*
            |--------------------------------------------------------------------------
            | Enviar correo de confirmación
            |--------------------------------------------------------------------------
            */

            $user = new User();

            $user->email = $pending->email;

            $user->notify(
            new VerifyPendingRegistration($pending)
            );

            return $pending;

        });
    }
        public function reenviar(string $email): bool
        {
            $pending = PendingRegistration::query()
                ->where('email', strtolower(trim($email)))
                ->first();

            if (! $pending) {
                return false;
            }

            $recipient = new User();

            $recipient->email = $pending->email;

            $recipient->notify(
                new VerifyPendingRegistration($pending)
            );

            return true;
        }

    

    /*
    |--------------------------------------------------------------------------
    | Buscar token
    |--------------------------------------------------------------------------
    */

    public function buscarPorToken(
        string $token
    ): ?PendingRegistration {

        return PendingRegistration::query()
            ->where('token', $token)
            ->first();

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar solicitud
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        PendingRegistration $pending
    ): void {

        $pending->delete();

    }
}
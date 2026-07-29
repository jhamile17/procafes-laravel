<?php

namespace App\Services\Cliente;

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
class PerfilService
{
    /**
     * Obtener datos del perfil.
     */
    public function getProfileData(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * Actualizar información personal.
     */
    public function updateProfile(
        User $user,
        array $data
    ): void {

        $user->update([

            'name' => User::construirNombreCompleto(
                $data['nombres'],
                $data['apellido_paterno'],
                $data['apellido_materno'] ?? null
            ),

            'nombres' => $data['nombres'],
            'apellido_paterno' => $data['apellido_paterno'],
            'apellido_materno' => $data['apellido_materno'] ?? null,
            'celular' => $data['celular'] ?? null,
        ]);

    }

    /**
     * Actualizar foto de perfil.
     */
    public function updatePhoto(
        User $user,
        UploadedFile $photo
    ): void {

        // Eliminar la foto anterior
        if (
            !empty($user->foto_perfil) &&
            Storage::disk('public')->exists($user->foto_perfil)
        ) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        // Guardar la nueva foto
        $path = $photo->store(
            'uploads/profile',
            'public'
        );

        // Actualizar usuario
        $user->update([
            'foto_perfil' => $path,
        ]);

    }
    public function updatePassword(
    User $user,
    array $data
    ): void {

        $update = [
            'password' => Hash::make(
                $data['password']
            ),
        ];
        // Si es la primera contraseña creada por un usuario de Google,
        // marcamos que ya dispone de contraseña local.
        if (! $user->has_local_password) {

            $update['has_local_password'] = true;

        }

        $user->update($update);
    }
}
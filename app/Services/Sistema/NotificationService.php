<?php

namespace App\Services\Sistema;

use App\Models\Notificacion;
use App\Models\User;
use App\Support\NotificationType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class NotificationService
{
    /*
    |--------------------------------------------------------------------------
    | Constructor
    |--------------------------------------------------------------------------
    */

    public function __construct()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Crear notificación
    |--------------------------------------------------------------------------
    */

    public function crear(
        int $userId,
        string $titulo,
        string $mensaje,
        string $tipo = NotificationType::INFO
    ): Notificacion {

        $this->validarUsuario($userId);

        $this->validarTipo($tipo);

        return DB::transaction(function () use (
            $userId,
            $titulo,
            $mensaje,
            $tipo
        ) {

            return Notificacion::create([

                'user_id' => $userId,

                'titulo' => $titulo,

                'mensaje' => $mensaje,

                'tipo' => strtolower($tipo),

                'leido' => false,

            ]);

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener notificación
    |--------------------------------------------------------------------------
    */

    public function obtener(
        int $notificationId
    ): Notificacion {

        return Notificacion::with('user')

            ->findOrFail($notificationId);

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todas las notificaciones
    |--------------------------------------------------------------------------
    */

    public function obtenerTodas(): Collection
    {

        return Notificacion::with('user')

            ->latest()

            ->get();

    }

    /*
    |--------------------------------------------------------------------------
    | Obtener notificaciones por usuario
    |--------------------------------------------------------------------------
    */

    public function obtenerPorUsuario(
        int $userId
    ): Collection {

        return Notificacion::where(
                'user_id',
                $userId
            )

            ->latest()

            ->get();

    }
        /*
    |--------------------------------------------------------------------------
    | Marcar notificación como leída
    |--------------------------------------------------------------------------
    */

    public function marcarComoLeida(
        Notificacion $notificacion
    ): Notificacion {

        if (!$notificacion->isLeida()) {

            $notificacion->marcarComoLeida();

        }

        return $notificacion->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Marcar todas las notificaciones como leídas
    |--------------------------------------------------------------------------
    */

    public function marcarTodasComoLeidas(
        int $userId
    ): int {

        $this->validarUsuario($userId);

        return Notificacion::where('user_id', $userId)
            ->where('leido', false)
            ->update([
                'leido' => true,
                'fecha_lectura' => now(),
            ]);

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar una notificación
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        Notificacion $notificacion
    ): bool {

        return $notificacion->delete();

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar todas las notificaciones de un usuario
    |--------------------------------------------------------------------------
    */

    public function eliminarTodas(
        int $userId
    ): int {

        $this->validarUsuario($userId);

        return Notificacion::where(
            'user_id',
            $userId
        )->delete();

    }

    /*
    |--------------------------------------------------------------------------
    | Contar notificaciones no leídas
    |--------------------------------------------------------------------------
    */

    public function contarNoLeidas(
        int $userId
    ): int {

        $this->validarUsuario($userId);

        return Notificacion::where(
            'user_id',
            $userId
        )

        ->where(
            'leido',
            false
        )

        ->count();

    }
        /*
    |--------------------------------------------------------------------------
    | Validar usuario
    |--------------------------------------------------------------------------
    */

    private function validarUsuario(
        int $userId
    ): void {

        User::query()

            ->findOrFail($userId);

    }

    /*
    |--------------------------------------------------------------------------
    | Validar tipo de notificación
    |--------------------------------------------------------------------------
    */

    private function validarTipo(
        string $tipo
    ): void {

        if (!NotificationType::exists($tipo)) {

            throw new InvalidArgumentException(
                'El tipo de notificación no es válido.'
            );

        }

    }
}
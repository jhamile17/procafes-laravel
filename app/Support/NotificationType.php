<?php

namespace App\Support;

final class NotificationType
{
    /*
    |--------------------------------------------------------------------------
    | Tipos de notificaciones
    |--------------------------------------------------------------------------
    */

    public const SUCCESS = 'success';

    public const INFO = 'info';

    public const WARNING = 'warning';

    public const ERROR = 'error';

    /**
     * Evita instanciar la clase.
     */
    private function __construct()
    {
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos auxiliares
    |--------------------------------------------------------------------------
    */

    public static function values(): array
    {
        return [
            self::SUCCESS,
            self::INFO,
            self::WARNING,
            self::ERROR,
        ];
    }

    public static function exists(string $type): bool
    {
        return in_array(
            strtolower($type),
            self::values(),
            true
        );
    }
}
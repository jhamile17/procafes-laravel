<?php

declare(strict_types=1);

namespace App\Services\Catalogo;

use App\Models\TipoConsumo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class TipoConsumoService
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
    | Crear tipo de consumo
    |--------------------------------------------------------------------------
    */

    public function crear(array $datos): TipoConsumo
    {
        return DB::transaction(function () use ($datos) {

            return TipoConsumo::create([
                'codigo' => $datos['codigo'],
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
                'status' => $datos['status'] ?? true,
            ]);

        });
    }

    /*
    |--------------------------------------------------------------------------
    | Actualizar tipo de consumo
    |--------------------------------------------------------------------------
    */

    public function actualizar(
        TipoConsumo $tipoConsumo,
        array $datos
    ): TipoConsumo {

        DB::transaction(function () use ($tipoConsumo, $datos) {

            $tipoConsumo->update([
                'codigo' => $datos['codigo'],
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'] ?? null,
                'status' => $datos['status'] ?? $tipoConsumo->status,
            ]);

        });

        return $tipoConsumo->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar tipo de consumo
    |--------------------------------------------------------------------------
    */

    public function eliminar(
        TipoConsumo $tipoConsumo
    ): bool {

        if (! $tipoConsumo->delete()) {

            throw new RuntimeException(
                'No fue posible eliminar el tipo de consumo.'
            );

        }

        return true;
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener tipo de consumo
    |--------------------------------------------------------------------------
    */

    public function obtener(int $id): TipoConsumo
    {
        return TipoConsumo::query()
            ->findOrFail($id);
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener todos los tipos de consumo
    |--------------------------------------------------------------------------
    */

    public function obtenerTodos(): Collection
    {
        return TipoConsumo::query()
            ->orderBy('nombre')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Obtener tipos de consumo activos
    |--------------------------------------------------------------------------
    */

    public function obtenerActivos(): Collection
    {
        return TipoConsumo::query()
            ->activos()
            ->orderBy('nombre')
            ->get();
    }
}
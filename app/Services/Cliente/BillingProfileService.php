<?php

declare(strict_types=1);

namespace App\Services\Cliente;

use App\Models\BillingProfile;
use App\Services\Integraciones\ReniecService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class BillingProfileService
{
    public function __construct(
        protected ReniecService $reniecService
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Listar perfiles
    |--------------------------------------------------------------------------
    */

    public function list(
        int $userId
        ): Collection {

            return BillingProfile::query()
                ->delUsuario($userId)
                ->activos()
                ->orderByDesc('predeterminado')
                ->orderBy('alias')
                ->get();

        }

    /*
    |--------------------------------------------------------------------------
    | Consultar RUC
    |--------------------------------------------------------------------------
    */

    public function searchRuc(string $ruc): array
    {
        return $this->reniecService
            ->consultarRuc($ruc);
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar perfil
    |--------------------------------------------------------------------------
    */

    public function create(
        User $user,
        array $data
    ): BillingProfile {

        return DB::transaction(function () use (
            $user,
            $data
        ) {

            $this->validateAlias(
                $user,
                $data['alias']
            );

            $this->validateRuc(
                $user,
                $data['ruc']
            );

            $isFirstProfile = ! BillingProfile::query()
                ->delUsuario($user->id)
                ->exists();

            return BillingProfile::create(
                $this->buildAttributes(
                    $user,
                    $data,
                    $isFirstProfile
                )
            );

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Eliminar perfil
    |--------------------------------------------------------------------------
    */

    public function delete(
        BillingProfile $profile
    ): void {

        if (
            $profile->electronicDocuments()->exists()
        ) {

            throw new RuntimeException(
                'No es posible eliminar un perfil que tiene comprobantes electrónicos asociados.'
            );

        }

        $profile->delete();

    }

    /*
    |--------------------------------------------------------------------------
    | Marcar como predeterminado
    |--------------------------------------------------------------------------
    */

    public function setDefault(
        BillingProfile $profile
    ): BillingProfile {

        if ($profile->esPredeterminado()) {

            return $profile;

        }

        $profile->marcarComoPredeterminado();

        return $profile->fresh();

    }

    /*
    |--------------------------------------------------------------------------
    | Validar alias
    |--------------------------------------------------------------------------
    */

    protected function validateAlias(
        User $user,
        string $alias
    ): void {

        $exists = BillingProfile::query()
            ->delUsuario($user->id)
            ->where('alias', trim($alias))
            ->exists();

        if ($exists) {

            throw new RuntimeException(
                'Ya existe una empresa registrada con ese nombre.'
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validar RUC
    |--------------------------------------------------------------------------
    */

    protected function validateRuc(
        User $user,
        string $ruc
    ): void {

        $exists = BillingProfile::query()
            ->delUsuario($user->id)
            ->where('ruc', trim($ruc))
            ->exists();

        if ($exists) {

            throw new RuntimeException(
                'El RUC ya se encuentra registrado.'
            );

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Construir atributos
    |--------------------------------------------------------------------------
    */

    protected function buildAttributes(
        User $user,
        array $data,
        bool $isFirstProfile
    ): array {

        return [

            'user_id' => $user->id,

            'alias' => trim($data['alias']),

            'ruc' => trim($data['ruc']),

            'razon_social' => trim($data['razon_social']),

            'direccion_fiscal' => trim($data['direccion_fiscal']),

            'predeterminado' => $isFirstProfile,

            'estado' => BillingProfile::ACTIVO,

        ];

    }
}
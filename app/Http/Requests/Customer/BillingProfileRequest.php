<?php

declare(strict_types=1);

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class BillingProfileRequest extends FormRequest
{
    /**
     * Determina si el usuario puede realizar esta solicitud.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Alias
            |--------------------------------------------------------------------------
            */

            'alias' => [
                'required',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | RUC
            |--------------------------------------------------------------------------
            */

            'ruc' => [
                'required',
                'digits:11',
            ],

            /*
            |--------------------------------------------------------------------------
            | Razón social
            |--------------------------------------------------------------------------
            */

            'razon_social' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Dirección fiscal
            |--------------------------------------------------------------------------
            */

            'direccion_fiscal' => [
                'required',
                'string',
                'max:255',
            ],

        ];
    }

    /**
     * Nombres amigables.
     */
    public function attributes(): array
    {
        return [

            'alias' => 'nombre de la empresa',

            'ruc' => 'RUC',

            'razon_social' => 'razón social',

            'direccion_fiscal' => 'dirección fiscal',

        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [

            'ruc.digits' => 'El RUC debe tener exactamente 11 dígitos.',

        ];
    }
}
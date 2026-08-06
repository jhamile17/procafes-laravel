<?php

declare(strict_types=1);

namespace App\Http\Requests\Checkout;

use App\Models\Comprobante;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
{
    /*
    |--------------------------------------------------------------------------
    | Autorización
    |--------------------------------------------------------------------------
    */

    public function authorize(): bool
    {
        return auth()->check();
    }

    /*
    |--------------------------------------------------------------------------
    | Reglas
    |--------------------------------------------------------------------------
    */

    public function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Método de entrega
            |--------------------------------------------------------------------------
            */

            'delivery_type' => [

                'required',

                Rule::in([

                    'pickup',

                    'delivery',

                ]),

            ],

            /*
            |--------------------------------------------------------------------------
            | Método de pago
            |--------------------------------------------------------------------------
            */

            'payment_method' => [

                'required',

                Rule::in([

                    'store',

                    'mercadopago',

                ]),

            ],

            /*
            |--------------------------------------------------------------------------
            | Tipo de comprobante
            |--------------------------------------------------------------------------
            */

            'tipo_comprobante' => [

                'required',

                Rule::in([

                    Comprobante::BOLETA,

                    Comprobante::FACTURA,

                ]),

            ],

            /*
            |--------------------------------------------------------------------------
            | Documento
            |--------------------------------------------------------------------------
            */

            'tipo_documento' => [

                'required',

                Rule::in([

                    Comprobante::DNI,

                    Comprobante::RUC,

                ]),

            ],

            'numero_documento' => [

                'required',

                'string',

                'max:20',

            ],

            /*
            |--------------------------------------------------------------------------
            | Boleta
            |--------------------------------------------------------------------------
            */

            'nombre' => [

                'required_if:tipo_comprobante,BOLETA',

                'nullable',

                'string',

                'max:255',

            ],

            /*
            |--------------------------------------------------------------------------
            | Factura
            |--------------------------------------------------------------------------
            */

            'razon_social' => [

                'required_if:tipo_comprobante,FACTURA',

                'nullable',

                'string',

                'max:255',

            ],

            /*
            |--------------------------------------------------------------------------
            | Dirección Fiscal
            |--------------------------------------------------------------------------
            */

            'direccion_fiscal' => [

                'required',

                'string',

                'max:255',

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mensajes
    |--------------------------------------------------------------------------
    */

    public function messages(): array
    {
        return [

            'delivery_type.required' =>
                'Selecciona un método de entrega.',

            'delivery_type.in' =>
                'El método de entrega seleccionado no es válido.',

            'payment_method.required' =>
                'Selecciona un método de pago.',

            'payment_method.in' =>
                'El método de pago seleccionado no es válido.',

            'tipo_comprobante.required' =>
                'Selecciona el tipo de comprobante.',

            'tipo_comprobante.in' =>
                'El tipo de comprobante no es válido.',

            'tipo_documento.required' =>
                'Selecciona el tipo de documento.',

            'tipo_documento.in' =>
                'El tipo de documento no es válido.',

            'numero_documento.required' =>
                'Ingresa el número de documento.',

            'nombre.required_if' =>
                'Ingresa el nombre del cliente.',

            'razon_social.required_if' =>
                'Ingresa la razón social.',

            'direccion_fiscal.required' =>
                'Ingresa la dirección fiscal.',

        ];
    }
    /*
|--------------------------------------------------------------------------
| Validaciones adicionales
|--------------------------------------------------------------------------
*/

public function withValidator($validator): void
{
    $validator->after(function ($validator) {

        $tipoComprobante = strtoupper(
            (string) $this->input('tipo_comprobante')
        );

        $tipoDocumento = strtoupper(
            (string) $this->input('tipo_documento')
        );

        /*
        |--------------------------------------------------------------------------
        | Boleta
        |--------------------------------------------------------------------------
        */

        if (
            $tipoComprobante === Comprobante::BOLETA &&
            $tipoDocumento !== Comprobante::DNI
        ) {

            $validator->errors()->add(
                'tipo_documento',
                'La boleta solo puede emitirse con DNI.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Factura
        |--------------------------------------------------------------------------
        */

        if (
            $tipoComprobante === Comprobante::FACTURA &&
            $tipoDocumento !== Comprobante::RUC
        ) {

            $validator->errors()->add(
                'tipo_documento',
                'La factura solo puede emitirse con RUC.'
            );

        }
        /*
        |--------------------------------------------------------------------------
        | Longitud del DNI
        |--------------------------------------------------------------------------
        */

        if (
            $tipoDocumento === Comprobante::DNI &&
            strlen($this->numero_documento) !== 8
        ) {

            $validator->errors()->add(
                'numero_documento',
                'El DNI debe tener 8 dígitos.'
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Longitud del RUC
        |--------------------------------------------------------------------------
        */

        if (
            $tipoDocumento === Comprobante::RUC &&
            strlen($this->numero_documento) !== 11
        ) {

            $validator->errors()->add(
                'numero_documento',
                'El RUC debe tener 11 dígitos.'
            );

        }

    });
    }
}
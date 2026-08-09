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
       return auth()->check();}

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

            'payment_method_id' => [
                'required',
                'numeric',
                Rule::in([1, 2]),
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
            | Tipo de documento
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
                'regex:/^[0-9]+$/',
            ],

            /*
            |--------------------------------------------------------------------------
            | Boleta
            |--------------------------------------------------------------------------
            */

            'nombre' => [
                'required_if:tipo_comprobante,' . Comprobante::BOLETA,
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
                'required_if:tipo_comprobante,' . Comprobante::FACTURA,
                'nullable',
                'string',
                'max:255',
            ],

            'direccion_fiscal' => [
                'required_if:tipo_comprobante,' . Comprobante::FACTURA,
                'nullable',
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

            'payment_method_id.required' =>
                'Selecciona un método de pago.',

            'payment_method_id.in' =>
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

            'numero_documento.regex' =>
                'El número de documento solo puede contener dígitos.',

            'nombre.required_if' =>
                'Ingresa el nombre del cliente.',

            'razon_social.required_if' =>
                'Ingresa la razón social.',

            'direccion_fiscal.required_if' =>
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

            $numeroDocumento = trim(
                (string) $this->input('numero_documento')
            );

            /*
            |--------------------------------------------------------------------------
            | Compatibilidad entre comprobante y documento
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
            | Longitud del documento
            |--------------------------------------------------------------------------
            */

            switch ($tipoDocumento) {

                case Comprobante::DNI:

                    if (strlen($numeroDocumento) !== 8) {

                        $validator->errors()->add(
                            'numero_documento',
                            'El DNI debe tener 8 dígitos.'
                        );
                    }

                    break;

                case Comprobante::RUC:

                    if (strlen($numeroDocumento) !== 11) {

                        $validator->errors()->add(
                            'numero_documento',
                            'El RUC debe tener 11 dígitos.'
                        );
                    }

                    break;

                default:
                    break;
            }

        });
    }
}
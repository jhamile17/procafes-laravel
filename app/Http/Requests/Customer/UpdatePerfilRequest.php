<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePerfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
{
    return [

        'nombres' => [
            'required',
            'string',
            'min:2',
            'max:100',
            'regex:/^[\pL\s]+$/u',
        ],

        'apellido_paterno' => [
            'required',
            'string',
            'min:2',
            'max:100',
            'regex:/^[\pL\s]+$/u',
        ],

        'apellido_materno' => [
            'required',
            'string',
            'min:2',
            'max:100',
            'regex:/^[\pL\s]+$/u',
        ],

        'celular' => [
            'required',
            'digits:9',
            'regex:/^9[0-9]{8}$/',
        ],
    ];
}
public function messages(): array
{
    return [

        'nombres.required' => 'Ingrese sus nombres.',
        'nombres.regex' => 'Los nombres solo pueden contener letras.',

        'apellido_paterno.required' => 'Ingrese el apellido paterno.',
        'apellido_paterno.regex' => 'El apellido paterno solo puede contener letras.',

        'apellido_materno.required' => 'Ingrese el apellido materno.',
        'apellido_materno.regex' => 'El apellido materno solo puede contener letras.',

        'celular.required' => 'Ingrese un número de celular.',
        'celular.digits' => 'El celular debe tener 9 dígitos.',
        'celular.regex' => 'El celular debe comenzar con 9.',
    ];
}
}
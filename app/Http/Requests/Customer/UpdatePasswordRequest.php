<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Validator;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Autorizar la solicitud.
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
        $rules = [

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

        ];

        if (auth()->user()->has_local_password) {

            $rules['current_password'] = [
                'required',
                'current_password',
            ];

        }

        return $rules;
    }

    /**
     * Validaciones adicionales.
     */
    public function after(): array
    {
        return [

            function (Validator $validator) {

                $user = $this->user();

                if (
                    $user->password &&
                    Hash::check($this->password, $user->password)
                ) {

                    $validator->errors()->add(
                        'password',
                        'La nueva contraseña debe ser diferente de la contraseña actual.'
                    );

                }

            },

        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [

            'current_password.required' => 'Debe ingresar su contraseña actual.',
            'current_password.current_password' => 'La contraseña actual es incorrecta.',

            'password.required' => 'Debe ingresar una nueva contraseña.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',

        ];
    }

    /**
     * Nombres amigables de los campos.
     */
    public function attributes(): array
    {
        return [

            'current_password' => 'contraseña actual',
            'password' => 'nueva contraseña',

        ];
    }
}
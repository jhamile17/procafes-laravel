<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Datos
    |--------------------------------------------------------------------------
    */

    #[Validate('required')]
    public string $token = '';

    #[Validate('required|email')]
    public string $email = '';

    #[Validate]
    public string $password = '';

    public string $password_confirmation = '';

    /*
    |--------------------------------------------------------------------------
    | Reglas
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            'password' => [
                'required',
                'confirmed',
                PasswordRule::defaults(),
            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Restablecer contraseña
    |--------------------------------------------------------------------------
    */

    public function resetPassword(): string
    {
        $this->validate();

        return Password::reset(

            [
                'email' => strtolower(trim($this->email)),
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],

            function ($user, string $password) {

                $user->forceFill([

                    'password' => Hash::make($password),

                    'remember_token' => Str::random(60),

                ])->save();

            }

        );
    }

    /*
    |--------------------------------------------------------------------------
    | Limpiar formulario
    |--------------------------------------------------------------------------
    */

    public function clear(): void
    {
        $this->reset();
    }
}
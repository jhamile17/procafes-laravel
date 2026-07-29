<?php
namespace App\Livewire\Forms;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Livewire\Form;

class ResetPasswordForm extends Form
{
    /*Datos*/
    public string $token = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    /*Reglas de validación*/
    protected function rules(): array
    {
        return [
            'token' => [
                'required',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ],
            'password' => [
                'required',
                'confirmed',
                PasswordRule::defaults(),
            ],
            'password_confirmation' => [
                'required',
            ],
        ];
    }

    /*Mensajes personalizados*/

    protected function messages(): array
    {
        return [
            'token.required' => 'El enlace de recuperación no es válido.',
            'email.required' => 'Ingrese su correo electrónico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
            'password.required' => 'Ingrese una contraseña.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password_confirmation.required' => 'Confirme su contraseña.',
        ];
    }

    /*Atributos personalizados*/
    protected function validationAttributes(): array
    {
        return [
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'password_confirmation' => 'confirmación de contraseña',
        ];
    }

    /*Restablecer contraseña*/

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

    /*Limpiar formulario*/
    public function clear(): void
    {
        $this->reset();
    }
}
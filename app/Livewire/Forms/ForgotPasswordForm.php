<?php
namespace App\Livewire\Forms;
use Illuminate\Support\Facades\Password;
use Livewire\Form;
class ForgotPasswordForm extends Form
{
    /*Datos*/
    public string $email = '';
    /*Reglas de validación*/
    protected function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],
        ];
    }

    /*Mensajes personalizados*/
    protected function messages(): array
    {
        return [
            'email.required' => 'Ingrese su correo electrónico.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede superar los 255 caracteres.',
        ];
    }

    /*Atributos personalizados*/
    protected function validationAttributes(): array
    {
        return [
            'email' => 'correo electrónico',
        ];
    }
    /*Enviar enlace de recuperación*/
    public function sendResetLink(): string
    {
        $this->validate();
        return Password::sendResetLink([
            'email' => strtolower(trim($this->email)),
        ]);
    }

    /*Limpiar formulario*/
    public function clear(): void
    {
        $this->reset();
    }
}
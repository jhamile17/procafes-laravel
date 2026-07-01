<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ForgotPasswordForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Datos
    |--------------------------------------------------------------------------
    */

    #[Validate('required|email')]
    public string $email = '';

    /*
    |--------------------------------------------------------------------------
    | Enviar enlace
    |--------------------------------------------------------------------------
    */

    public function sendResetLink(): string
    {
        $this->validate();

        return Password::sendResetLink([
            'email' => strtolower(trim($this->email)),
        ]);
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
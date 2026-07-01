<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\ResetPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ResetPassword extends Component
{
    public ResetPasswordForm $form;

    public function mount(string $token): void
    {
        $this->form->token = $token;

        $this->form->email = request()->query('email', '');
    }

    /*
    |--------------------------------------------------------------------------
    | Restablecer contraseña
    |--------------------------------------------------------------------------
    */

    public function resetPassword(): void
    {
        $status = $this->form->resetPassword();

        if ($status === Password::PASSWORD_RESET) {

            session()->flash(

                'status',

                'Tu contraseña fue actualizada correctamente.'

            );

            $this->redirectRoute('login');

            return;
        }

        $this->addError(

            'form.email',

            __($status)

        );
    }

    public function render()
    {
        return view('livewire.pages.auth.reset-password')
            ->layout('layouts.auth');
    }
}
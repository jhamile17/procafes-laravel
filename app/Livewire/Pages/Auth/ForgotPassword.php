<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\ForgotPasswordForm;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public ForgotPasswordForm $form;

    /*
    |--------------------------------------------------------------------------
    | Enviar enlace
    |--------------------------------------------------------------------------
    */

    public function sendResetLink(): void
    {
        $status = $this->form->sendResetLink();

        if ($status === Password::RESET_LINK_SENT) {

            session()->flash(
                'status',
                __($status)
            );

            $this->form->clear();

            return;
        }

        $this->addError(
            'form.email',
            __($status)
        );
    }

    public function render()
    {
        return view('livewire.pages.auth.forgot-password')
            ->layout('layouts.auth');
    }
}
<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;

class VerifyEmail extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Reenviar correo
    |--------------------------------------------------------------------------
    */

    public function resendVerificationEmail(): void
    {
        if (! auth()->check()) {
            return;
        }

        if (auth()->user()->hasVerifiedEmail()) {

            $this->redirectRoute('login');

            return;

        }

        auth()->user()->sendEmailVerificationNotification();

        session()->flash(

            'status',

            'Te enviamos un nuevo enlace de verificación a tu correo.'

        );
    }

    public function render()
    {
        return view('livewire.pages.auth.verify-email')
            ->layout('layouts.auth');
    }
}
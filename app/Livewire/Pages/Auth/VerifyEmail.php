<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;

class VerifyEmail extends Component
{
    public function resendVerificationEmail(): void
    {
        session()->flash(
            'status',
            'Si no encuentras el correo, revisa Spam o espera unos minutos antes de solicitar otro enlace.'
        );
    }

    public function render()
    {
        return view('livewire.pages.auth.verify-email')
            ->layout('layouts.auth');
    }
}
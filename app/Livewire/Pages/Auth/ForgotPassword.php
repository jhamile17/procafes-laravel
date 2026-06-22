<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public string $email = '';

    public function sendResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
        ]);

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            session()->flash('status', __($status));

            return;
        }

        $this->addError('email', __($status));
    }

    public function render()
    {
        return view('livewire.pages.auth.forgot-password')
            ->layout('layouts.auth');
    }
}
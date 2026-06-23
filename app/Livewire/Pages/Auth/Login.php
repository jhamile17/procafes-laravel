<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public array $state = [
        'email' => '',
        'password' => '',
        'remember' => false,
    ];

    public function mount()
    {
        if (Auth::check()) {
            return $this->afterLoginRedirect();
        }

        return null;
    }

    public function login()
    {
        $this->validate([
            'state.email' => ['required', 'email'],
            'state.password' => ['required', 'string'],
        ]);

        $remember = (bool) ($this->state['remember'] ?? false);

        if (! Auth::attempt([
            'email' => $this->state['email'],
            'password' => $this->state['password'],
        ], $remember)) {
            throw ValidationException::withMessages([
                'state.email' => __('auth.failed'),
            ]);
        }

        session()->regenerate();

        return $this->afterLoginRedirect();
    }

    private function afterLoginRedirect()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        /*
         * No eliminamos url.intended.
         * Así, si llegó desde /checkout, Laravel recuerda ese destino
         * incluso si antes debe verificar el correo.
         */
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('customer.dashboard'));
    }

    public function render()
    {
        return view('livewire.pages.auth.login')
            ->layout('layouts.app');
    }
}
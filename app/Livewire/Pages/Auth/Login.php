<?php

namespace App\Livewire\Pages\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
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
        |--------------------------------------------------------------------------
        | Usuario registrado con correo y aún no verificado
        |--------------------------------------------------------------------------
        | Google guarda email_verified_at porque Google ya confirmó el correo.
        */
        if (! $user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        /*
        |--------------------------------------------------------------------------
        | Administrador
        |--------------------------------------------------------------------------
        */
        if ($user->isAdmin()) {
            return redirect()->intended(route('admin.dashboard'));
        }

        /*
        |--------------------------------------------------------------------------
        | Cliente
        |--------------------------------------------------------------------------
        */
        if (session()->has('url.intended')) {
            return redirect()->intended(route('customer.dashboard'));
        }

        return redirect()->route('customer.dashboard');
    }
    
}
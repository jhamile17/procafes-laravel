<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        if (Auth::check()) {

            $this->redirectAfterLogin();

        }
    }

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login(): void
    {
        $this->form->authenticate();

        session()->regenerate();

        $this->redirectAfterLogin();
    }

    /*
    |--------------------------------------------------------------------------
    | Redirección
    |--------------------------------------------------------------------------
    */

    private function redirectAfterLogin(): void
    {
        $user = Auth::user();

        if (! $user) {

            $this->redirectRoute('login');

            return;

        }

        if (! $user->hasVerifiedEmail()) {

            $this->redirectRoute('verification.notice');

            return;

        }

        if ($user->isAdmin()) {

            $this->redirect('/admin/dashboard');

            return;
        }

        $this->redirect('/cliente');
    }

    public function render()
    {
        return view('livewire.pages.auth.login')
            ->layout('layouts.auth');
    }
}
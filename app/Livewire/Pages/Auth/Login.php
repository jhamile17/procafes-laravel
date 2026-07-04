<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\LoginForm;
use App\Services\Ventas\CartService;
use App\Services\Ventas\SessionCartService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public LoginForm $form;
    protected CartService $cartService;
    protected SessionCartService $sessionCartService;
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

        $this->sessionCartService->sincronizar(

            request(),

            $this->cartService,

            auth()->id()

        );

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

            $this->redirectRoute('admin.dashboard');

            return;

        }

        $this->redirectRoute('products');
    }
    public function boot(
    CartService $cartService,
    SessionCartService $sessionCartService,
    ): void {

        $this->cartService = $cartService;

        $this->sessionCartService = $sessionCartService;

    }

    public function render()
    {
        return view('livewire.pages.auth.login')
            ->layout('layouts.auth');
    }
}
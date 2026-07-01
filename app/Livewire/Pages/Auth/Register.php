<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\RegisterForm;
use App\Services\Auth\UserRegistrationService;
use Livewire\Component;

class Register extends Component
{
    public RegisterForm $form;

    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */

    public function register(
        UserRegistrationService $registrationService
    )
    {
        $this->form->register(
            $registrationService
        );

        session()->flash(

            'success',

            'Tu cuenta fue creada correctamente. Revisa tu correo para verificarla antes de iniciar sesión.'

        );

        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.pages.auth.register')
            ->layout('layouts.auth');
    }
}
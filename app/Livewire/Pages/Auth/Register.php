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
        $this->validate();

        $this->form->normalizarDatos();

        $registrationService->register([

            'nombres' => $this->form->nombres,

            'apellido_paterno' => $this->form->apellido_paterno,

            'apellido_materno' => $this->form->apellido_materno,

            'tipo_documento' => $this->form->tipo_documento,

            'numero_documento' => $this->form->numero_documento,

            'email' => $this->form->email,

            'password' => $this->form->password,

            'celular' => $this->form->celular,

        ]);

        $this->form->clear();

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
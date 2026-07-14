<?php

namespace App\Livewire\Pages\Auth;

use App\Livewire\Forms\RegisterForm;
use App\Services\Auth\PendingRegistrationService;
use App\Services\Integraciones\ReniecService;
use Livewire\Component;

class Register extends Component
{
    public RegisterForm $form;

    /*
    |--------------------------------------------------------------------------
    | Buscar documento
    |--------------------------------------------------------------------------
    */

    public function buscarDocumento(
        ReniecService $reniecService
    ): void {
        $inicio = microtime(true);

        $respuesta = $reniecService->consultarDni(
            $this->form->numero_documento
        );

        logger('Tiempo RENIEC: ' . (microtime(true) - $inicio));

        $this->form->estadoDocumento = RegisterForm::DOCUMENTO_SIN_CONSULTAR;

        $this->form->permitirEdicionManual = true;

        $this->form->documentoConsultado = false;

        $this->form->numero_documento = trim(
            $this->form->numero_documento
        );

        if ($this->form->tipo_documento !== 'DNI') {
            return;
        }

        if (strlen($this->form->numero_documento) !== 8) {
            return;
        }

        $this->form->estadoDocumento = RegisterForm::DOCUMENTO_CONSULTANDO;

        $respuesta = $reniecService->consultarDni(
            $this->form->numero_documento
        );

        if (
            ! $respuesta['success']
            || empty($respuesta['data'])
            || ! ($respuesta['data']['success'] ?? false)
        ) {

            $this->form->estadoDocumento = RegisterForm::DOCUMENTO_NO_ENCONTRADO;

            $this->form->permitirEdicionManual = true;

            $this->form->documentoConsultado = true;

            return;
        }

        $persona = $respuesta['data'];

        $this->form->nombres = trim(
            $persona['nombres'] ?? ''
        );

        $this->form->apellido_paterno = trim(
            $persona['apellidoPaterno'] ?? ''
        );

        $this->form->apellido_materno = trim(
            $persona['apellidoMaterno'] ?? ''
        );

        $this->form->estadoDocumento = RegisterForm::DOCUMENTO_ENCONTRADO;

        $this->form->permitirEdicionManual = false;

        $this->form->documentoConsultado = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */
   public function register(
    PendingRegistrationService $pendingService
    )
    {
        $email = $this->form->email;

        $this->form->register(
            $pendingService
        );

        $this->form->clear();

        session([
        'registration_email' => $email,
        ]);

        return redirect()->route(
            'register.check-email'
        );
    }

    public function puedeBuscarDocumento(): bool
    {
        return match ($this->form->tipo_documento) {
            'DNI' => strlen($this->form->numero_documento) === 8,
            'RUC' => strlen($this->form->numero_documento) === 11,
            default => false,
        };
    }

    public function render()
    {
        return view('livewire.pages.auth.register')
            ->layout('layouts.auth');
    }
}
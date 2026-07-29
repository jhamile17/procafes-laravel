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
    | Estado de contraseña
    |--------------------------------------------------------------------------
    */

    public bool $passwordsMatch = true;

    public bool $checkingPassword = false;

    /*
    |--------------------------------------------------------------------------
    | Consultar documento
    |--------------------------------------------------------------------------
    */

    public function consultarDocumento(
        ReniecService $reniecService
    ): void {

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

        $inicio = microtime(true);

        $respuesta = $reniecService->consultarDni(
            $this->form->numero_documento
        );

        logger('Tiempo RENIEC: ' . (microtime(true) - $inicio));

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
    | Detectar cambios en el número de documento
    |--------------------------------------------------------------------------
    */

    public function updatedFormNumeroDocumento(
        $value
    ): void {

        $this->form->numero_documento = trim($value);

        $this->form->estadoDocumento = RegisterForm::DOCUMENTO_SIN_CONSULTAR;

        $this->form->permitirEdicionManual = true;

        $this->form->documentoConsultado = false;

        if (
            $this->form->tipo_documento === 'DNI'
            && strlen($this->form->numero_documento) === 8
        ) {

            $this->consultarDocumento(
                app(ReniecService::class)
            );

            return;
        }

        if (
            $this->form->tipo_documento === 'RUC'
            && strlen($this->form->numero_documento) === 11
        ) {

            // Preparado para futura consulta RUC

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Validación en tiempo real
    |--------------------------------------------------------------------------
    */

    public function updated($property): void
    {
        // La contraseña se valida visualmente.
        // La regla confirmed solo se ejecutará al enviar el formulario.
        if (
            in_array($property, [
                'form.password',
                'form.password_confirmation',
            ])
        ) {

            $this->validarPasswords();

            return;
        }

        $this->validateOnly($property);
    }

    /*
    |--------------------------------------------------------------------------
    | Detectar cambio de tipo de documento
    |--------------------------------------------------------------------------
    */

    public function updatedFormTipoDocumento(): void
    {

        $this->form->numero_documento = '';

        $this->form->nombres = '';

        $this->form->apellido_paterno = '';

        $this->form->apellido_materno = '';

        $this->form->estadoDocumento = RegisterForm::DOCUMENTO_SIN_CONSULTAR;

        $this->form->permitirEdicionManual = true;

        $this->form->documentoConsultado = false;

    }

    /*
    |--------------------------------------------------------------------------
    | Validación visual de contraseña
    |--------------------------------------------------------------------------
    */

    public function updatedFormPassword(): void
    {
        $this->validarPasswords();
    }

    public function updatedFormPasswordConfirmation(): void
    {
        $this->validarPasswords();
    }

    protected function validarPasswords(): void
    {
        $this->checkingPassword =
            filled($this->form->password)
            && filled($this->form->password_confirmation);

        $this->passwordsMatch =
            ! $this->checkingPassword
            || $this->form->password === $this->form->password_confirmation;
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */

    public function register(
        PendingRegistrationService $pendingService
    ) {

        $email = $this->form->email;

        $this->form->register(
            $pendingService
        );

        $this->form->clear();

        $this->passwordsMatch = true;

        $this->checkingPassword = false;

        session([
            'registration_email' => $email,
        ]);

        return redirect()->route(
            'register.check-email'
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.pages.auth.register')
            ->layout('layouts.auth');
    }
}
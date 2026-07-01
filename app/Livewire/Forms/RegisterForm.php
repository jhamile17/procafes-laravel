<?php

namespace App\Livewire\Forms;

use App\Models\User;
use App\Services\Auth\UserRegistrationService;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RegisterForm extends Form
{
    protected UserRegistrationService $registrationService;
    public function boot(UserRegistrationService $registrationService): void
    {
        $this->registrationService = $registrationService;
    }

    #[Validate('required|string|max:20')]
    public string $tipo_documento = 'DNI';

    #[Validate('required|string|max:20|unique:users,numero_documento')]
    public string $numero_documento = '';

    #[Validate('required|string|max:100')]
    public string $nombres = '';

    #[Validate('required|string|max:100')]
    public string $apellido_paterno = '';

    #[Validate('nullable|string|max:100')]
    public string $apellido_materno = '';

    /*
    |--------------------------------------------------------------------------
    | Contacto
    |--------------------------------------------------------------------------
    */

    #[Validate('required|email|max:255|unique:users,email')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $celular = '';

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    #[Validate]
    public string $password = '';

    public string $password_confirmation = '';
     /*
    |--------------------------------------------------------------------------
    | Estado del documento
    |--------------------------------------------------------------------------
    */

    public const DOCUMENTO_SIN_CONSULTAR = 'sin_consultar';

    public const DOCUMENTO_CONSULTANDO = 'consultando';

    public const DOCUMENTO_ENCONTRADO = 'encontrado';

    public const DOCUMENTO_NO_ENCONTRADO = 'no_encontrado';

    public string $estadoDocumento = self::DOCUMENTO_SIN_CONSULTAR;
    /*
    |--------------------------------------------------------------------------
    | Estado de la interfaz
    |--------------------------------------------------------------------------
    */

    public bool $permitirEdicionManual = true;

    public bool $documentoConsultado = false;

    /*
    |--------------------------------------------------------------------------
    | Reglas
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Buscar documento (Preparado para RENIEC)
    |--------------------------------------------------------------------------
    */

    public function buscarDocumento(): void
    {
        $this->estadoDocumento = self::DOCUMENTO_SIN_CONSULTAR;

        $this->permitirEdicionManual = true;

        $this->documentoConsultado = false;

        if ($this->tipo_documento !== 'DNI') {
            return;
        }

        if (strlen(trim($this->numero_documento)) !== 8) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Aquí consumiremos ReniecService
        |--------------------------------------------------------------------------
        */

        $this->estadoDocumento = self::DOCUMENTO_CONSULTANDO;

        /*
        |--------------------------------------------------------------------------
        | Temporal
        |--------------------------------------------------------------------------
        */

        $this->estadoDocumento = self::DOCUMENTO_NO_ENCONTRADO;

        $this->permitirEdicionManual = true;

        $this->documentoConsultado = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */

   public function register(
    UserRegistrationService $registrationService
    ): User{
        $this->validate();
        $this->normalizarDatos();
        return $registrationService->register([
            'tipo_documento' => $this->tipo_documento,
            'numero_documento' => $this->numero_documento,
            'nombres' => $this->nombres,
            'apellido_paterno' => $this->apellido_paterno,
            'apellido_materno' => $this->apellido_materno,
            'email' => $this->email,
            'password' => $this->password,
            'celular' => $this->celular,
        ]);
    }
      protected function normalizarDatos(): void
    {
        $this->nombres = trim($this->nombres);

        $this->apellido_paterno = trim($this->apellido_paterno);

        $this->apellido_materno = trim($this->apellido_materno);

        $this->numero_documento = trim($this->numero_documento);

        $this->email = strtolower(
            trim($this->email)
        );

        $this->celular = trim($this->celular);
    }
    /*
    |--------------------------------------------------------------------------
    | Limpiar formulario
    |--------------------------------------------------------------------------
    */

    public function clear(): void
    {
        $this->reset();
        $this->tipo_documento = 'DNI';

        $this->estadoDocumento = self::DOCUMENTO_SIN_CONSULTAR;

        $this->permitirEdicionManual = true;

        $this->documentoConsultado = false;
    }
}
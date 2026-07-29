<?php

namespace App\Livewire\Forms;

use App\Services\Auth\PendingRegistrationService;
use Illuminate\Validation\Rules\Password;
use Livewire\Form;

class RegisterForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Información personal
    |--------------------------------------------------------------------------
    */

    public string $tipo_documento = 'DNI';

    public string $numero_documento = '';

    public string $nombres = '';

    public string $apellido_paterno = '';

    public string $apellido_materno = '';

    /*
    |--------------------------------------------------------------------------
    | Contacto
    |--------------------------------------------------------------------------
    */

    public string $email = '';

    public string $celular = '';

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

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
    | Reglas de validación
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Documento
            |--------------------------------------------------------------------------
            */

            'tipo_documento' => [
                'required',
                'string',
                'max:20',
            ],

            'numero_documento' => match ($this->tipo_documento) {

                'DNI' => [
                    'required',
                    'digits:8',
                    'unique:users,numero_documento',
                ],

                'RUC' => [
                    'required',
                    'digits:11',
                    'unique:users,numero_documento',
                ],

                'CE' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:users,numero_documento',
                ],

                'PASAPORTE' => [
                    'required',
                    'string',
                    'max:20',
                    'unique:users,numero_documento',
                ],

                default => [
                    'required',
                    'string',
                    'max:20',
                ],

            },

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            'nombres' => [
                'required',
                'string',
                'max:100',
            ],

            'apellido_paterno' => [
                'required',
                'string',
                'max:100',
            ],

            'apellido_materno' => [
                'nullable',
                'string',
                'max:100',
            ],

            /*
            |--------------------------------------------------------------------------
            | Contacto
            |--------------------------------------------------------------------------
            */

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'celular' => [
                'nullable',
                'digits:9',
                'regex:/^9\d{8}$/',
            ],

            /*
            |--------------------------------------------------------------------------
            | Seguridad
            |--------------------------------------------------------------------------
            */

            'password' => [

                'required',

                'confirmed',

                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers(),

            ],

            'password_confirmation' => [

                'required',

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Mensajes personalizados
    |--------------------------------------------------------------------------
    */

    protected function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | Documento
            |--------------------------------------------------------------------------
            */

            'tipo_documento.required' => 'Seleccione un tipo de documento.',

            'numero_documento.required' => 'Ingrese el número de documento.',

            'numero_documento.digits' => 'El número de documento no tiene el formato correcto.',

            'numero_documento.unique' => 'Este número de documento ya está registrado.',

            /*
            |--------------------------------------------------------------------------
            | Datos personales
            |--------------------------------------------------------------------------
            */

            'nombres.required' => 'Ingrese sus nombres.',

            'apellido_paterno.required' => 'Ingrese su apellido paterno.',

            /*
            |--------------------------------------------------------------------------
            | Contacto
            |--------------------------------------------------------------------------
            */

            'email.required' => 'Ingrese un correo electrónico.',

            'email.email' => 'Ingrese un correo electrónico válido.',

            'email.unique' => 'Este correo electrónico ya está registrado.',

            'celular.regex' => 'Ingrese un numero valido.El celular debe tener 9 dígitos y comenzar con 9',

            /*
            |--------------------------------------------------------------------------
            | Seguridad
            |--------------------------------------------------------------------------
            */

            'password.required' => 'Ingrese una contraseña.',

            'password.confirmed' => 'Las contraseñas no coinciden.',

            'password_confirmation.required' => 'Confirme su contraseña.',
            'password.letters' => 'La contraseña debe contener al menos una letra.',

            'password.mixed' => 'La contraseña debe contener al menos una letra mayúscula y una letra minúscula.',

            'password.numbers' => 'La contraseña debe contener al menos un número.',

            'password.symbols' => 'La contraseña debe contener al menos un símbolo.',

            'password.uncompromised' => 'La contraseña no es segura. Elija otra diferente.',

            'password_confirmation.required' => 'Confirme su contraseña.',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Nombres amigables para los atributos
    |--------------------------------------------------------------------------
    */

    protected function validationAttributes(): array
    {
        return [

            'tipo_documento' => 'tipo de documento',

            'numero_documento' => 'número de documento',

            'apellido_paterno' => 'apellido paterno',

            'apellido_materno' => 'apellido materno',

            'password_confirmation' => 'confirmación de contraseña',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Registrar usuario
    |--------------------------------------------------------------------------
    */

    public function register(
        PendingRegistrationService $pendingService
    ): void {

        $this->validate();

        $this->normalizarDatos();

        $pendingService->registrar([

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

    /*
    |--------------------------------------------------------------------------
    | Normalizar datos
    |--------------------------------------------------------------------------
    */

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
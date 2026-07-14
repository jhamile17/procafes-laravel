<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\WelcomeToProcafes;
use App\Services\Auth\PendingRegistrationService;
use App\Services\Auth\UserRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class CompleteRegistrationController extends Controller
{
    public function __construct(
        protected PendingRegistrationService $pendingService,
        protected UserRegistrationService $userRegistrationService,
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Completar registro
    |--------------------------------------------------------------------------
    */

    public function __invoke(string $token): RedirectResponse
    {
        $pending = $this->pendingService
            ->buscarPorToken($token);

        if (! $pending) {

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'El enlace ya no es válido.',
                ]);

        }

        if ($pending->expirado()) {

            $this->pendingService
                ->eliminar($pending);

            return redirect()
                ->route('register')
                ->withErrors([
                    'email' => 'El enlace ha expirado.',
                ]);

        }

        /*
        |--------------------------------------------------------------------------
        | Crear usuario
        |--------------------------------------------------------------------------
        */

        $user = $this->userRegistrationService->register([
        'nombres' => $pending->nombres,
        'apellido_paterno' => $pending->apellido_paterno,
        'apellido_materno' => $pending->apellido_materno,
        'tipo_documento' => $pending->tipo_documento,
        'numero_documento' => $pending->numero_documento,
        'celular' => $pending->telefono,
        'email' => $pending->email,
        'password' => Crypt::decryptString(
            $pending->password
        ),
        'email_verified_at' => now(),
    ]);

        /*
        |--------------------------------------------------------------------------
        | Eliminar registro pendiente
        |--------------------------------------------------------------------------
        */

        $this->pendingService
            ->eliminar($pending);

        /*
        |--------------------------------------------------------------------------
        | Correo de bienvenida
        |--------------------------------------------------------------------------
        */

        $user->notify(
            new WelcomeToProcafes()
        );

        /*
        |--------------------------------------------------------------------------
        | Login
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        request()->session()->regenerate();
    
        return redirect()->route(
            'register.welcome'
        );
          
    }
}
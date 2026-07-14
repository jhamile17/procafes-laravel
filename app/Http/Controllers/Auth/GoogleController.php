<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\UserRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Redirección Login
    |--------------------------------------------------------------------------
    */
    public function redirectLogin(): RedirectResponse
    {
        session([
            'google_flow' => 'login',
        ]);
        return $this->googleProvider()
            ->scopes([
                'openid',
                'profile',
                'email',
            ])
            ->with([
                'prompt'=>'select_account'
            ])
            ->redirect();
    }
    /*
    |--------------------------------------------------------------------------
    | Redirección Registro
    |--------------------------------------------------------------------------
    */
    public function redirectRegister(): RedirectResponse
    {
        session([
            'google_flow' => 'register',
        ]);

        return $this->googleProvider()
            ->scopes([
                'openid',
                'profile',
                'email',
            ])
            ->with([
                'prompt' => 'select_account'
            ])
            ->redirect();
    }
    /*
    |--------------------------------------------------------------------------
    | Callback Google
    |--------------------------------------------------------------------------
    */
    public function callback(
        UserRegistrationService $registrationService
    ): RedirectResponse {
        try {

            $googleUser = $this->googleProvider()->user();
            $email = strtolower(
                trim((string) $googleUser->getEmail())
            );
            if ($email === '') {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'google' => 'Google no devolvió un correo electrónico válido.',
                    ]);
            }
            $flow = session()->pull(
                'google_flow',
                'login'
            );
            $user = User::query()
                ->whereRaw(
                    'LOWER(email) = ?',
                    [$email]
                )
                ->first();
            /*
            |--------------------------------------------------------------------------
            | Registro con Google
            |--------------------------------------------------------------------------
            */
            if ($flow === 'register') {

                if ($user) {

                    return redirect()
                        ->route('register')
                        ->withErrors([
                            'email' => 'Este correo ya se encuentra registrado.',
                        ]);
                }

                $fullName = trim(
                    (string) (
                        $googleUser->getName()
                        ?: 'Usuario'
                    )
                );

                $parts = preg_split(
                    '/\s+/',
                    $fullName
                );

                $nombres = $parts[0] ?? 'Usuario';

                $apellidoPaterno = $parts[1] ?? '';

                $apellidoMaterno = count($parts) >= 3
                    ? implode(
                        ' ',
                        array_slice($parts, 2)
                    )
                    : '';

                $user = $registrationService->register([
                    'nombres' => $nombres,
                    'apellido_paterno' => $apellidoPaterno,
                    'apellido_materno' => $apellidoMaterno,
                    'tipo_documento' => 'PENDIENTE',
                    'numero_documento' => 'PENDIENTE',
                    'email' => $email,
                    'password' => bin2hex(
                        random_bytes(32)
                    ),
                    'provider' => User::PROVIDER_GOOGLE,
                    'provider_id' => $googleUser->getId(),
                    'celular' => '',
                    'direccion' => '',
                    'foto_perfil' => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Login
            |--------------------------------------------------------------------------
            */

            if (! $user) {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'email' => 'No existe una cuenta registrada con este correo.',
                    ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Vincular proveedor
            |--------------------------------------------------------------------------
            */

            if (
                empty($user->provider)
                || $user->provider === User::PROVIDER_LOCAL
            ) {

                $user = $registrationService->linkProvider(

                    $user,

                    User::PROVIDER_GOOGLE,

                    $googleUser->getId()

                );

            } elseif (

                $user->provider === User::PROVIDER_GOOGLE

                && $user->provider_id !== $googleUser->getId()

            ) {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'google' => 'La cuenta de Google no coincide con el proveedor registrado.',
                    ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Actualizar último acceso
            |--------------------------------------------------------------------------
            */

            $registrationService->updateLastAccess(
                $user
            );

            /*
            |--------------------------------------------------------------------------
            | Actualizar foto de perfil
            |--------------------------------------------------------------------------
            */

            if (

                $googleUser->getAvatar()

                && $user->foto_perfil !== $googleUser->getAvatar()

            ) {

                $user->update([

                    'foto_perfil' => $googleUser->getAvatar(),

                ]);

            }

            /*
            |--------------------------------------------------------------------------
            | Iniciar sesión
            |--------------------------------------------------------------------------
            */

            Auth::login(
                $user,
                true
            );

            request()
                ->session()
                ->regenerate();

            /*
            |--------------------------------------------------------------------------
            | Redirección
            |--------------------------------------------------------------------------
            */

            if ($user->isAdmin()) {

                return redirect()->intended(
                    route('admin.dashboard')
                );
            }

            return redirect()->intended(
                route('customer.dashboard')
            );
        } catch (\Throwable $exception) {
            Log::error(
                'Google Login Error',
                [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ]
            );
            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'No fue posible iniciar sesión con Google.',
                ]);
        }
    }
    /*
    |--------------------------------------------------------------------------
    | Provider
    |--------------------------------------------------------------------------
    */
    protected function googleProvider(): Provider
    {
        $provider = Socialite::driver('google');

        if (app()->environment('local')) {
            $provider->stateless();
        }
        return $provider;
    }
}
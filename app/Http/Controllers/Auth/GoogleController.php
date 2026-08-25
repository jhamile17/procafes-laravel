<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\UserRegistrationService;
use App\Services\Ventas\CartService;
use App\Services\Ventas\SessionCartService;
use App\Services\Ventas\SessionWishlistService;
use App\Services\Ventas\WishlistService;
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
                'prompt' => 'select_account',
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
                'prompt' => 'select_account',
            ])
            ->redirect();
    }


    /*
    |--------------------------------------------------------------------------
    | Callback Google
    |--------------------------------------------------------------------------
    */

    public function callback(
        UserRegistrationService $registrationService,
        CartService $cartService,
        SessionCartService $sessionCartService,
        SessionWishlistService $sessionWishlistService,
        WishlistService $wishlistService
    ): RedirectResponse {

        try {

            /*
            |--------------------------------------------------------------------------
            | Obtener usuario de Google
            |--------------------------------------------------------------------------
            */

            $googleUser = $this->googleProvider()->user();

            $email = strtolower(
                trim((string) $googleUser->getEmail())
            );


            /*
            |--------------------------------------------------------------------------
            | Validar correo
            |--------------------------------------------------------------------------
            */

            if ($email === '') {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'form.email' =>
                            'Google no devolvió un correo electrónico válido.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Obtener flujo
            |--------------------------------------------------------------------------
            */

            $flow = session()->pull(
                'google_flow',
                'login'
            );


            Log::info('GOOGLE CALLBACK', [
                'flow' => $flow,
                'session_id' => session()->getId(),
                'email' => $email,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Buscar usuario
            |--------------------------------------------------------------------------
            */

            $user = User::query()
                ->whereRaw(
                    'LOWER(email) = ?',
                    [$email]
                )
                ->first();


            /*
            |--------------------------------------------------------------------------
            | REGISTRO CON GOOGLE
            |--------------------------------------------------------------------------
            */

            if ($flow === 'register') {

                /*
                |------------------------------------------------------------------
                | El correo ya existe
                |------------------------------------------------------------------
                */

                if ($user) {

                    /*
                    |--------------------------------------------------------------------------
                    | CUENTA DESACTIVADA
                    |--------------------------------------------------------------------------
                    |
                    | Si el correo ya pertenece a una cuenta desactivada,
                    | no permitimos registrarla nuevamente.
                    |
                    */

                    if (! $user->isActive()) {

                        return redirect()
                            ->route('register')
                            ->withErrors([
                                'form.email' =>
                                    'Esta cuenta está desactivada. Comunícate con el administrador.',
                            ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Cuenta Google
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $user->provider === User::PROVIDER_GOOGLE
                        && ! $user->has_local_password
                    ) {

                        return redirect()
                            ->route('register')
                            ->withErrors([
                                'form.email' =>
                                    'Este correo ya está registrado con Google. Inicia sesión usando "Continuar con Google".',
                            ]);
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Cuenta tradicional
                    |--------------------------------------------------------------------------
                    */

                    return redirect()
                        ->route('register')
                        ->withErrors([
                            'form.email' =>
                                'Este correo electrónico ya está registrado. Si ya tienes una cuenta, inicia sesión.',
                        ]);
                }


                /*
                |--------------------------------------------------------------------------
                | Crear nueva cuenta Google
                |--------------------------------------------------------------------------
                */

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

                    'nombres' =>
                        $nombres,

                    'apellido_paterno' =>
                        $apellidoPaterno,

                    'apellido_materno' =>
                        $apellidoMaterno,

                    'tipo_documento' =>
                        null,

                    'numero_documento' =>
                        null,

                    'email' =>
                        $email,

                    'password' =>
                        bin2hex(random_bytes(32)),

                    'has_local_password' =>
                        false,

                    'provider' =>
                        User::PROVIDER_GOOGLE,

                    'provider_id' =>
                        $googleUser->getId(),

                    'celular' =>
                        '',

                    'direccion' =>
                        '',

                    'foto_perfil' =>
                        $googleUser->getAvatar(),

                    'email_verified_at' =>
                        now(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | LOGIN CON GOOGLE
            |--------------------------------------------------------------------------
            */

            if (! $user) {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'form.email' =>
                            'No existe una cuenta registrada con este correo. Puedes registrarte primero.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | VERIFICAR ESTADO DE LA CUENTA
            |--------------------------------------------------------------------------
            |
            | ESTA ES LA PARTE NUEVA E IMPORTANTE.
            |
            | Si el administrador desactivó al usuario,
            | Google tampoco podrá iniciar sesión.
            |
            */

            if (! $user->isActive()) {

                Log::warning(
                    'Intento de acceso Google de usuario desactivado.',
                    [
                        'user_id' =>
                            $user->id,

                        'email' =>
                            $user->email,
                    ]
                );

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'form.email' =>
                            'Tu cuenta está desactivada. Comunícate con el administrador.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Cuenta Google
            |--------------------------------------------------------------------------
            */

            if (
                $user->provider === User::PROVIDER_GOOGLE
                && $user->provider_id !== $googleUser->getId()
            ) {

                return redirect()
                    ->route('login')
                    ->withErrors([
                        'form.email' =>
                            'Esta cuenta está vinculada a otro perfil de Google.',
                    ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Vincular Google con cuenta local
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
            | Obtener favoritos de sesión
            |--------------------------------------------------------------------------
            */

            $sessionWishlist =
                $sessionWishlistService
                    ->obtener(request());


            /*
            |--------------------------------------------------------------------------
            | Iniciar sesión
            |--------------------------------------------------------------------------
            */

            Auth::login(
                $user,
                true
            );


            /*
            |--------------------------------------------------------------------------
            | Sincronizar carrito
            |--------------------------------------------------------------------------
            */

            $sessionCartService->sincronizar(
                request(),
                $cartService,
                $user->id
            );


            /*
            |--------------------------------------------------------------------------
            | Transferir favoritos
            |--------------------------------------------------------------------------
            */

            $wishlistService->transferirFavoritos(
                $user->id,
                $sessionWishlist
            );


            $sessionWishlistService->vaciar(
                request()
            );


            /*
            |--------------------------------------------------------------------------
            | Regenerar sesión
            |--------------------------------------------------------------------------
            */

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
                route('products')
            );

        } catch (\Throwable $exception) {

            Log::error(
                'Google Login Error',
                [
                    'message' =>
                        $exception->getMessage(),

                    'file' =>
                        $exception->getFile(),

                    'line' =>
                        $exception->getLine(),

                    'trace' =>
                        $exception->getTraceAsString(),
                ]
            );


            return redirect()
                ->route('login')
                ->withErrors([
                    'form.email' =>
                        'No fue posible iniciar sesión con Google. Inténtalo nuevamente.',
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
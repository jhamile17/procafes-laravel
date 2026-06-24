<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PendingRegistration;
use App\Models\User;
use App\Notifications\ConfirmPendingRegistrationEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Contracts\Provider;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectLogin()
    {
        session(['google_flow' => 'login']);

        return $this->googleProvider()
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function redirectRegister()
    {
        session(['google_flow' => 'register']);

        return $this->googleProvider()
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = $this->googleProvider()->user();

            $email = strtolower(trim((string) $googleUser->getEmail()));

            if ($email === '') {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'google' => 'Google no devolvió una dirección de correo.',
                    ]);
            }

            $flow = session()->pull('google_flow', 'login');

            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            /*
             * REGISTRO CON GOOGLE:
             * Si el correo no existe, todavía NO creamos User.
             * Guardamos la solicitud pendiente y enviamos confirmación.
             */
            if ($flow === 'register' && ! $user) {
                $pending = PendingRegistration::updateOrCreate(
                    ['email' => $email],
                    [
                        'name' => $googleUser->getName()
                            ?: data_get($googleUser->user, 'given_name', 'Usuario'),
                        'phone' => null,
                        'password' => Hash::make(Str::random(40)),
                        'token' => Str::random(64),
                        'expires_at' => now()->addMinutes(60),
                    ]
                );

                $pending->notify(new ConfirmPendingRegistrationEmail($pending));

                return redirect()
                    ->route('register')
                    ->with('status', 'Te enviamos un enlace de confirmación. Tu cuenta se creará cuando confirmes tu correo.');
            }

            /*
             * Si intenta registrarse con Google pero el correo ya existe,
             * no iniciar sesión automáticamente.
             */
            if ($flow === 'register' && $user) {
                return redirect()
                    ->route('register')
                    ->withErrors([
                        'email' => 'Este correo ya está registrado. Inicia sesión para continuar.',
                    ]);
            }

            /*
             * LOGIN CON GOOGLE:
             * Solo permite entrar si el usuario ya existe.
             * Si no existe, debe ir a Registro.
             */
            if (! $user) {
                return redirect()
                    ->route('register')
                    ->withErrors([
                        'email' => 'No existe una cuenta con este correo. Regístrate primero.',
                    ]);
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));

        } catch (\Throwable $e) {
            Log::error('Error en autenticación con Google', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($e),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'No se pudo continuar con Google. Intenta nuevamente.',
                ]);
        }
    }

    private function googleProvider(): Provider
    {
        $provider = Socialite::driver('google');

        if (app()->environment('local')) {
            $provider->stateless();
        }

        return $provider;
    }
}
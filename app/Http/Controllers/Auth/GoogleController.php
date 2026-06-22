<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
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

            // Sabemos si llegó desde Login o Registro.
            $flow = session()->pull('google_flow', 'login');

            // Busca ignorando mayúsculas/minúsculas.
            $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

            /*
             * Si vino desde Registro y el correo ya existe,
             * no iniciar sesión: volver al registro con mensaje.
             */
            if ($flow === 'register' && $user) {
                return redirect()
                    ->route('register')
                    ->withErrors([
                        'email' => 'Este correo ya está registrado. Inicia sesión para continuar.',
                    ]);
            }

            /*
             * Desde Login o Registro, si el correo no existe,
             * se crea una cuenta nueva.
             */
            if (! $user) {
                $user = new User();

                $user->name = $googleUser->getName()
                    ?: data_get($googleUser->user, 'given_name', 'Usuario');

                $user->email = $email;
                $user->password = Hash::make(Str::random(40));

                if (Schema::hasColumn($user->getTable(), 'role')) {
                    $user->role = User::ROLE_CUSTOMER;
                }

                if (Schema::hasColumn($user->getTable(), 'address')) {
                    $user->address = null;
                }

                if (Schema::hasColumn($user->getTable(), 'phone')) {
                    $user->phone = null;
                }

                if (Schema::hasColumn($user->getTable(), 'document_type')) {
                    $user->document_type = null;
                }

                if (Schema::hasColumn($user->getTable(), 'document_number')) {
                    $user->document_number = null;
                }

                // PROCAFES solicita su propia verificación.
                if (Schema::hasColumn($user->getTable(), 'email_verified_at')) {
                    $user->email_verified_at = null;
                }

                $user->save();

                event(new Registered($user));
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            if (! $user->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));

        } catch (\Throwable $e) {
            Log::error('Error en login con Google', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'class' => get_class($e),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'google' => 'No se pudo iniciar sesión con Google. Intenta nuevamente.',
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
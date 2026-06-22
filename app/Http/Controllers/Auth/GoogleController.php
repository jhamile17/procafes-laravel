<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->scopes(['openid', 'profile', 'email'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $email = $googleUser->getEmail();

            if (! $email) {
                return redirect()
                    ->route('login')
                    ->withErrors([
                        'state.email' => 'Google no devolvió una dirección de correo.',
                    ]);
            }

            $user = User::where('email', $email)->first();

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

                if (Schema::hasColumn($user->getTable(), 'email_verified_at')) {
                    // Google ya confirmó que controla ese correo.
                    $user->email_verified_at = now();
                }
                
                $user->save();
            }

            Auth::login($user, true);
            request()->session()->regenerate();

            return redirect()->intended(route('home'));
        } catch (\Throwable $e) {
            Log::error('Error en login con Google', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()
                ->route('login')
                ->withErrors([
                    'state.email' => 'No se pudo iniciar sesión con Google. Revisa el registro de Laravel.',
                ]);
        }
    }
}
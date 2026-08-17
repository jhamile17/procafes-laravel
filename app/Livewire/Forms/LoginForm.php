<?php

namespace App\Livewire\Forms;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;

class LoginForm extends Form
{
    /*
    |--------------------------------------------------------------------------
    | Datos
    |--------------------------------------------------------------------------
    */

    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    #[Validate('boolean')]
    public bool $remember = false;

    /*
    |--------------------------------------------------------------------------
    | Autenticar
    |--------------------------------------------------------------------------
    */

    public function authenticate(): void
    {
        $this->validate();

        $this->email = strtolower(trim($this->email));

        $this->ensureIsNotRateLimited();

        /*
        |--------------------------------------------------------------------------
        | Buscar usuario
        |--------------------------------------------------------------------------
        */

        $user = User::where('email', $this->email)->first();

        /*
        |--------------------------------------------------------------------------
        | Correo no registrado
        |--------------------------------------------------------------------------
        */

        if (! $user) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' =>
                    'No existe una cuenta registrada con este correo. Puedes registrarte para crear una cuenta.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Cuenta sin contraseña local
        |--------------------------------------------------------------------------
        */

        if (! $user->has_local_password) {

            if ($user->provider === User::PROVIDER_GOOGLE) {

                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'form.email' =>
                        'Esta cuenta fue registrada con Google. Inicia sesión usando "Continuar con Google".',
                ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Login tradicional
        |--------------------------------------------------------------------------
        */

        if (! Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
        ], $this->remember)) {

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'form.email' =>
                    'La contraseña ingresada es incorrecta.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Login correcto
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear($this->throttleKey());
    }

    /*
    |--------------------------------------------------------------------------
    | Limitar intentos
    |--------------------------------------------------------------------------
    */

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn(
            $this->throttleKey()
        );

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Llave del RateLimiter
    |--------------------------------------------------------------------------
    */

    protected function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->email) . '|' . request()->ip()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Limpiar formulario
    |--------------------------------------------------------------------------
    */

    public function clear(): void
    {
        $this->reset();

        $this->remember = false;
    }
}
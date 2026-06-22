<?php

namespace App\Livewire\Pages\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register()
    {
        $data = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['nullable', 'string', 'max:20'],
        ],
        [
            'email.unique' => 'Este correo ya está registrado. Inicia sesión o recupera tu contraseña.',
            'email.required' => 'Ingresa tu correo electrónico.',
            'email.email' => 'Ingresa un correo electrónico válido.',
        ]
        
        );

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?: null,
            'address' => null,
            'document_type' => null,
            'document_number' => null,
            'role' => User::ROLE_CUSTOMER,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.pages.auth.register')
            ->layout('layouts.auth');
    }
    public function updatedEmail(): void
{
    $this->validateOnly('email', [
        'email' => [
            'required',
            'email',
            'max:255',
            Rule::unique('users', 'email'),
        ],
    ], [
        'email.required' => 'Ingresa tu correo electrónico.',
        'email.email' => 'Ingresa un correo electrónico válido.',
        'email.unique' => 'Este correo ya está registrado. Inicia sesión o recupera tu contraseña.',
    ]);
}
}
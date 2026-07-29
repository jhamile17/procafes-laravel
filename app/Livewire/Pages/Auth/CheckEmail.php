<?php

namespace App\Livewire\Pages\Auth;

use Livewire\Component;

class CheckEmail extends Component
{
    public string $email = '';

    public int $seconds = 60;

    public bool $canResend = false;

    public function mount(): void
    {
        $this->email = session('registration_email', '');

        if ($this->email === '') {
            abort(404);
        }
    }

    public function render()
    {
        return view('livewire.pages.auth.check-email')
            ->layout('layouts.auth');
    }
}
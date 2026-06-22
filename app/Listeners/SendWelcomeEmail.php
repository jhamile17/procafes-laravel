<?php

namespace App\Listeners;

use App\Notifications\WelcomeToProcafes;
use Illuminate\Auth\Events\Verified;

class SendWelcomeEmail
{
    public function handle(Verified $event): void
    {
        $event->user->notify(new WelcomeToProcafes());
    }
}
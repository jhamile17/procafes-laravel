<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;

class WelcomeVerifyEmail extends VerifyEmail
{
    /**
     * Generar URL de verificación (válida 60 min)
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1(
                    $notifiable->getEmailForVerification()
                ),
            ]
        );
    }

    /**
     * Correo personalizado
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                'Confirma tu correo y ¡bienvenido a PROCAFES!'
            )
            ->view(
                'emails.verify-welcome',
                [
                    'user' => $notifiable,
                    'url' => $this->verificationUrl(
                        $notifiable
                    ),
                ]
            );
    }
}
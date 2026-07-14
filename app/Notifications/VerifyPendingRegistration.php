<?php

namespace App\Notifications;

use App\Models\PendingRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyPendingRegistration extends Notification
{
    use Queueable;

    public function __construct(
        protected PendingRegistration $pending
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | Canales
    |--------------------------------------------------------------------------
    */

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /*
    |--------------------------------------------------------------------------
    | Correo
    |--------------------------------------------------------------------------
    */

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirma tu cuenta en PROCÁFES')
            ->view(
                'emails.confirm-pending-registration',
                [
                    'pending' => $this->pending,
                    'url' => route(
                        'register.complete',
                        $this->pending->token
                    ),
                ]
            );
    }
}
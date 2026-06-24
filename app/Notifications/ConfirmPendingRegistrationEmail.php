<?php

namespace App\Notifications;
use App\Models\PendingRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class ConfirmPendingRegistrationEmail extends Notification
{
    use Queueable;

   
    public function __construct(
        public PendingRegistration $pendingRegistration){
        }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
       $url = URL::temporarySignedRoute(
            'pending-registration.confirm',
            Carbon::now()->addMinutes(60),
            ['token' => $this->pendingRegistration->token]
        );

        return (new MailMessage)
            ->subject('Confirma tu correo para crear tu cuenta - Procafes')
            ->view('emails.confirm-pending-registration', [
                'pending' => $this->pendingRegistration,
                'url' => $url,
            ]);
    }


    }


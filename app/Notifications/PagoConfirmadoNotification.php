<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PagoConfirmadoNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Order $order
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                'Pago confirmado - '
                . $this->order->numero_pedido
            )
            ->view('emails.pago-confirmado', [
                'user' => $notifiable,
                'order' => $this->order,
            ]);
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}
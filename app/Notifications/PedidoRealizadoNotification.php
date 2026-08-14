<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PedidoRealizadoNotification extends Notification{ 
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
                'Tu pedido '.$this->order->numero_pedido.' fue registrado'
            )
            ->view('emails.pedido-realizado', [
                'user' => $notifiable,
                'order' => $this->order,
            ]);
    }
}
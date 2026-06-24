<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UsuarioReactivacion extends Notification
{
    use Queueable;

    public $productos;

    public function __construct($productos)
    {
        $this->productos = $productos;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail = (new MailMessage)
            ->subject('¡Te extrañamos en PROCAFES!')
            ->greeting('Hola ' . $notifiable->name)
            ->line('Tenemos productos que te podrían gustar 👇');

        foreach ($this->productos as $producto) {
            $mail->line('- ' . $producto->name . ' S/ ' . $producto->price);
        }

        return $mail->action('Ver productos', url('/'))
                    ->line('¡Te esperamos!');
    }
}
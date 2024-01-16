<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordReset extends Notification
{
    use Queueable;
    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $expires = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
        ->subject('Notificação de redefinição de senha!')
        ->greeting('Olá!')
        // ->salutation('Atenciosamente! PRODEC')
        ->line('Você está recebendo esta mensagem porque houve uma requisição de redefinição de senha.')
        ->action('Redefinição de Senha', route('password.reset', $this->token))
        ->line('Este link de redefinição de senha irá expirar em '.$expires.' minutos')
        ->line('Se você não solicitou redefinição de senha, ignore.');
    
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

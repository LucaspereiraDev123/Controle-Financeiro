<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

/**
 * E-mail de redefinição de senha, em PT-BR e enfileirado.
 *
 * Estende a notificação nativa do Laravel para traduzir o conteúdo e
 * reaproveitar a geração da URL de reset (resetUrl). O token é recebido
 * pelo construtor herdado. Implementa ShouldQueue (fila `database`).
 */
class RedefinirSenhaNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    /**
     * @param  \App\Models\Usuario  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        $minutos = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Redefinição de senha — Aí Economiza')
            ->greeting('Olá!')
            ->line('Recebemos um pedido para redefinir a senha da sua conta.')
            ->action('Redefinir senha', $this->resetUrl($notifiable))
            ->line("Este link de redefinição expira em {$minutos} minutos.")
            ->line('Se você não solicitou a redefinição, ignore este e-mail.')
            ->salutation('Abraços, equipe Aí Economiza');
    }
}

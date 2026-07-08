<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

/**
 * E-mail de verificação de conta, em PT-BR e enfileirado.
 *
 * Estende a notificação nativa do Laravel apenas para traduzir o conteúdo
 * e reaproveitar a geração da URL assinada (verificationUrl). Implementa
 * ShouldQueue para não bloquear o cadastro enquanto o SMTP responde — o
 * envio roda na fila `database` (requer um `queue:work` ativo).
 */
class VerificarEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * @param  \App\Models\Usuario  $notifiable
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirme seu e-mail — Aí Economiza')
            ->greeting('Olá!')
            ->line('Falta pouco para começar a organizar suas finanças no Aí Economiza.')
            ->line('Clique no botão abaixo para confirmar seu endereço de e-mail.')
            ->action('Confirmar e-mail', $this->verificationUrl($notifiable))
            ->line('Se você não criou uma conta, nenhuma ação é necessária.')
            ->salutation('Abraços, equipe Aí Economiza');
    }
}

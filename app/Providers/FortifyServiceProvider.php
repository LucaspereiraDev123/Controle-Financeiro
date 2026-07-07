<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // Telas de autenticação (mantidas em PT-BR).
        Fortify::loginView(fn () => view('login.index'));
        Fortify::registerView(fn () => view('usuarios.create'));
        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));
        Fortify::resetPasswordView(fn (Request $request) => view('auth.reset-password', ['request' => $request]));
        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        // E-mails de autenticação traduzidos para PT-BR. O envio é síncrono por
        // ora; ao migrar para o provedor real (Fase 6/produção) podemos trocar
        // por notificações ShouldQueue usando a fila `database` já configurada.
        VerifyEmail::toMailUsing(function (object $notifiable, string $url): MailMessage {
            return (new MailMessage)
                ->subject('Confirme seu e-mail — Economiza Aí')
                ->greeting('Olá!')
                ->line('Falta pouco para começar a organizar suas finanças no Economiza Aí.')
                ->line('Clique no botão abaixo para confirmar seu endereço de e-mail.')
                ->action('Confirmar e-mail', $url)
                ->line('Se você não criou uma conta, nenhuma ação é necessária.')
                ->salutation('Abraços, equipe Economiza Aí');
        });

        ResetPassword::toMailUsing(function (object $notifiable, string $token): MailMessage {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $minutos = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

            return (new MailMessage)
                ->subject('Redefinição de senha — Economiza Aí')
                ->greeting('Olá!')
                ->line('Recebemos um pedido para redefinir a senha da sua conta.')
                ->action('Redefinir senha', $url)
                ->line("Este link de redefinição expira em {$minutos} minutos.")
                ->line('Se você não solicitou a redefinição, ignore este e-mail.')
                ->salutation('Abraços, equipe Economiza Aí');
        });

        // Limita o login a 5 tentativas por minuto por e-mail + IP.
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });
    }
}

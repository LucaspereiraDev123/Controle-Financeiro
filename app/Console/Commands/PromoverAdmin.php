<?php

namespace App\Console\Commands;

use App\Models\Usuario;
use Illuminate\Console\Command;

/**
 * Promove (ou rebaixa) uma conta de usuário a administrador pelo e-mail.
 *
 *   php artisan admin:promover usuario@exemplo.com
 *   php artisan admin:promover usuario@exemplo.com --remover
 */
class PromoverAdmin extends Command
{
    protected $signature = 'admin:promover {email : E-mail do usuário} {--remover : Remove o acesso de admin em vez de conceder}';

    protected $description = 'Concede ou remove o papel de administrador de uma conta';

    public function handle(): int
    {
        $usuario = Usuario::where('email', $this->argument('email'))->first();

        if (! $usuario) {
            $this->error("Nenhum usuário encontrado com o e-mail {$this->argument('email')}.");

            return self::FAILURE;
        }

        $usuario->is_admin = ! $this->option('remover');
        $usuario->save();

        $this->info($usuario->is_admin
            ? "{$usuario->email} agora é administrador."
            : "{$usuario->email} não é mais administrador.");

        return self::SUCCESS;
    }
}

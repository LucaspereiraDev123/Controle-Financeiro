<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        // Esconde as ações de escrita de quem está sem assinatura nem trial.
        // O bloqueio de verdade é o middleware `assinatura` nas rotas; isto
        // evita oferecer um botão que só levaria ao redirecionamento.
        Blade::if('podeEditar', fn () => auth()->user()?->podeEditar() ?? false);
    }
}

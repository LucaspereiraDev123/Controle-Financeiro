<?php

use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\AssinaturaAtiva;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Atrás de proxy/túnel (ngrok em dev, load balancer em produção) que
        // termina o TLS: confiar nos cabeçalhos X-Forwarded-* para que a app
        // reconheça o esquema https e gere URLs/assets corretos (evita mixed content).
        $middleware->trustProxies(at: '*');

        $middleware->alias([
            'assinatura' => AssinaturaAtiva::class,
            'admin' => AdminOnly::class,
        ]);

        // O webhook do Mercado Pago é chamado pelo MP (sem token CSRF).
        $middleware->validateCsrfTokens(except: [
            'webhooks/mercadopago',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

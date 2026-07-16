<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\TransacoesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AssinaturaController;
use App\Http\Controllers\ContaController;
use App\Http\Controllers\MercadoPagoWebhookController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminUsuarioController;

/*
 * As rotas de autenticação (login, registro, logout, recuperação de senha e
 * verificação de e-mail) são registradas automaticamente pelo Laravel Fortify.
 */

// Site público (não exige login). A landing redireciona quem já está logado.
Route::get('/', [SiteController::class, 'home'])->name('home');
Route::get('/planos', [SiteController::class, 'planos'])->name('planos');
Route::get('/privacidade', [SiteController::class, 'privacidade'])->name('privacidade');
Route::get('/termos', [SiteController::class, 'termos'])->name('termos');

Route::middleware(['auth', 'verified'])->group(function () {
    // Checkout da assinatura. O cliente chega aqui pela página de planos.
    Route::post('/assinatura/checkout', [AssinaturaController::class, 'checkout'])->name('assinatura.checkout');
    Route::get('/assinatura/retorno', [AssinaturaController::class, 'retorno'])->name('assinatura.retorno');

    // Minha conta: dados do cliente e situação do plano. É só leitura, e o
    // cliente com assinatura expirada precisa dela para se resolver.
    Route::get('/conta', [ContaController::class, 'index'])->name('conta');

    // Painel de administração (só usuários is_admin). Fora do middleware
    // `assinatura`: gerir o sistema não depende de ter assinatura própria.
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/usuarios', [AdminUsuarioController::class, 'index'])->name('usuarios');
        Route::post('/usuarios/{usuario}/estender', [AdminUsuarioController::class, 'estenderAcesso'])->name('usuarios.estender');
        Route::post('/usuarios/{usuario}/bloquear', [AdminUsuarioController::class, 'bloquearAcesso'])->name('usuarios.bloquear');
    });

    // Áreas do app. Consultar é livre: sem assinatura nem trial o cliente
    // continua vendo o que registrou, em modo leitura. Só as rotas que ALTERAM
    // dados exigem assinatura vigente — daí o middleware por método.
    Route::resource('/categorias', CategoriasController::class)
        ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'assinatura');

    // Força o nome do parâmetro para casar com o type-hint Transacao $transacao
    // (o Laravel singularizaria "transacoes" como "transaco", quebrando o binding).
    Route::resource('/transacoes', TransacoesController::class)
        ->parameters(['transacoes' => 'transacao'])
        ->middlewareFor(['create', 'store', 'edit', 'update', 'destroy'], 'assinatura');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/filtro', [DashboardController::class, 'filtroDashboard'])->name('filtro');
});

// Webhook do Mercado Pago (público, sem auth/CSRF — chamado pelo MP).
Route::post('/webhooks/mercadopago', MercadoPagoWebhookController::class)->name('webhooks.mercadopago');

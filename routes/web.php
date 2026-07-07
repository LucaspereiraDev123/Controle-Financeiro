<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\TransacoesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SiteController;

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
    Route::resource('/categorias', CategoriasController::class);
    // Força o nome do parâmetro para casar com o type-hint Transacao $transacao
    // (o Laravel singularizaria "transacoes" como "transaco", quebrando o binding).
    Route::resource('/transacoes', TransacoesController::class)
        ->parameters(['transacoes' => 'transacao']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/filtro', [DashboardController::class, 'filtroDashboard'])->name('filtro');
});

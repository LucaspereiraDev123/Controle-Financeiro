<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriasController;
use App\Http\Controllers\TransacoesController;
use App\Http\Controllers\DashboardController;

/*
 * As rotas de autenticação (login, registro, logout, recuperação de senha e
 * verificação de e-mail) são registradas automaticamente pelo Laravel Fortify.
 */

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('/categorias', CategoriasController::class);
    // Força o nome do parâmetro para casar com o type-hint Transacao $transacao
    // (o Laravel singularizaria "transacoes" como "transaco", quebrando o binding).
    Route::resource('/transacoes', TransacoesController::class)
        ->parameters(['transacoes' => 'transacao']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/filtro', [DashboardController::class, 'filtroDashboard'])->name('filtro');
});

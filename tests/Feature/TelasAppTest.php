<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Garante que as telas do app (transações e categorias) renderizam no novo
 * layout dark sem erro de Blade, para um usuário com acesso liberado.
 */
class TelasAppTest extends TestCase
{
    use RefreshDatabase;

    private function usuarioComAcesso(): Usuario
    {
        $usuario = Usuario::create([
            'nome' => 'Fulano',
            'email' => 'fulano' . uniqid() . '@teste.com',
            'password' => Hash::make('SenhaForte123'),
            'trial_ends_at' => now()->addDays(10),
            'termos_aceitos_em' => now(),
        ]);
        $usuario->markEmailAsVerified();

        return $usuario;
    }

    public function test_telas_de_listagem_e_criacao_renderizam(): void
    {
        $usuario = $this->usuarioComAcesso();

        $this->actingAs($usuario)->get(route('transacoes.index'))->assertOk();
        $this->actingAs($usuario)->get(route('transacoes.create'))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.index'))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.create'))->assertOk();
    }

    public function test_telas_de_detalhe_e_edicao_renderizam(): void
    {
        $usuario = $this->usuarioComAcesso();
        $categoria = $usuario->categorias()->create(['nome' => 'Salário', 'tipo' => 'Receitas']);
        $transacao = $usuario->transacoes()->create([
            'tipo' => 'Receitas',
            'descricao' => 'Salário',
            'valor' => 5000,
            'categoria_id' => $categoria->id,
        ]);

        $this->actingAs($usuario)->get(route('transacoes.show', $transacao->id))->assertOk();
        $this->actingAs($usuario)->get(route('transacoes.edit', $transacao->id))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.show', $categoria->id))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.edit', $categoria->id))->assertOk();
    }
}

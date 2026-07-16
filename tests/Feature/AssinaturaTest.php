<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AssinaturaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Cria um usuário verificado com controle das datas de trial/assinatura.
     */
    private function usuario(?string $trial, ?string $assinatura = null): Usuario
    {
        $usuario = Usuario::create([
            'nome' => 'Fulano',
            'email' => 'fulano' . uniqid() . '@teste.com',
            'password' => Hash::make('SenhaForte123'),
            'trial_ends_at' => $trial,
            'assinatura_ativa_ate' => $assinatura,
            'termos_aceitos_em' => now(),
        ]);

        $usuario->markEmailAsVerified();

        return $usuario;
    }

    /**
     * Dá ao usuário uma categoria e uma transação, para exercitar as telas de
     * listagem/detalhe e as rotas que recebem um id.
     *
     * @return array{0: \App\Models\Categoria, 1: \App\Models\Transacao}
     */
    private function dadosDe(Usuario $usuario): array
    {
        $categoria = $usuario->categorias()->create(['nome' => 'Salário', 'tipo' => 'Receitas']);
        $transacao = $usuario->transacoes()->create([
            'tipo' => 'Receitas',
            'descricao' => 'Salário do mês',
            'valor' => 5000,
            'categoria_id' => $categoria->id,
        ]);

        return [$categoria, $transacao];
    }

    public function test_trial_ativo_acessa_o_dashboard(): void
    {
        $usuario = $this->usuario(trial: now()->addDays(5)->toDateTimeString());

        $this->actingAs($usuario)->get('/dashboard')->assertOk();
    }

    public function test_trial_expirado_ve_o_dashboard_em_modo_leitura(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('modo leitura')
            ->assertDontSee('Nova transação');
    }

    public function test_expirado_acessa_listagens_e_detalhes(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());
        [$categoria, $transacao] = $this->dadosDe($usuario);

        $this->actingAs($usuario)->get(route('transacoes.index'))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.index'))->assertOk();
        $this->actingAs($usuario)->get(route('transacoes.show', $transacao))->assertOk();
        $this->actingAs($usuario)->get(route('categorias.show', $categoria))->assertOk();
    }

    /**
     * O cliente vê os dados que já registrou — é o ponto do modo leitura.
     */
    public function test_expirado_continua_vendo_os_proprios_dados(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());
        $this->dadosDe($usuario);

        $this->actingAs($usuario)
            ->get(route('transacoes.index'))
            ->assertOk()
            ->assertSee('Salário do mês');
    }

    public function test_expirado_e_barrado_nas_rotas_de_escrita(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());
        [$categoria, $transacao] = $this->dadosDe($usuario);

        $rotas = [
            ['get', route('transacoes.create')],
            ['post', route('transacoes.store')],
            ['get', route('transacoes.edit', $transacao)],
            ['put', route('transacoes.update', $transacao)],
            ['delete', route('transacoes.destroy', $transacao)],
            ['get', route('categorias.create')],
            ['post', route('categorias.store')],
            ['get', route('categorias.edit', $categoria)],
            ['put', route('categorias.update', $categoria)],
            ['delete', route('categorias.destroy', $categoria)],
        ];

        foreach ($rotas as [$verbo, $url]) {
            $this->actingAs($usuario)
                ->{$verbo}($url)
                ->assertRedirect(route('planos'));
        }
    }

    public function test_escrita_de_expirado_nao_altera_o_banco(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());
        [, $transacao] = $this->dadosDe($usuario);

        $this->actingAs($usuario)->delete(route('transacoes.destroy', $transacao));

        $this->assertDatabaseHas('transacoes', ['id' => $transacao->id]);
    }

    public function test_quem_tem_acesso_ve_os_botoes_de_acao(): void
    {
        $usuario = $this->usuario(trial: now()->addDays(5)->toDateTimeString());

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Nova transação')
            ->assertDontSee('modo leitura');
    }

    public function test_assinatura_paga_acessa_mesmo_com_trial_expirado(): void
    {
        $usuario = $this->usuario(
            trial: now()->subDays(10)->toDateTimeString(),
            assinatura: now()->addMonth()->toDateTimeString(),
        );

        $this->actingAs($usuario)->get('/dashboard')->assertOk();
    }

    public function test_planos_no_painel_oferece_assinar_para_expirado(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->actingAs($usuario)
            ->get(route('planos'))
            ->assertOk()
            ->assertSee('Assinar agora')
            ->assertSee(route('assinatura.checkout'));
    }

    public function test_planos_no_painel_avisa_o_trial_sobre_os_dias_perdidos(): void
    {
        $usuario = $this->usuario(trial: now()->addDays(5)->toDateTimeString());

        $this->actingAs($usuario)
            ->get(route('planos'))
            ->assertOk()
            ->assertSee('Assinar agora')
            ->assertSee('perdidos');
    }

    public function test_planos_nao_oferece_assinar_para_quem_ja_paga(): void
    {
        $usuario = $this->usuario(
            trial: now()->subDays(10)->toDateTimeString(),
            assinatura: now()->addMonth()->toDateTimeString(),
        );

        $this->actingAs($usuario)
            ->get(route('planos'))
            ->assertOk()
            ->assertSee('Assinatura ativa')
            ->assertDontSee('Assinar agora');
    }

    public function test_planos_para_visitante_usa_a_pagina_publica(): void
    {
        $this->get(route('planos'))
            ->assertOk()
            ->assertSee('Começar teste grátis');
    }

    public function test_planos_mostra_o_preco_da_configuracao(): void
    {
        config(['services.mercadopago.plano_valor' => 29.90]);
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->get(route('planos'))->assertSee('29,90');
        $this->actingAs($usuario)->get(route('planos'))->assertSee('29,90');
    }

    public function test_status_da_assinatura(): void
    {
        $trial = $this->usuario(trial: now()->addDays(5)->toDateTimeString());
        $ativa = $this->usuario(
            trial: now()->subDay()->toDateTimeString(),
            assinatura: now()->addMonth()->toDateTimeString(),
        );
        $expirada = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->assertSame('trial', $trial->statusAssinatura());
        $this->assertSame('ativa', $ativa->statusAssinatura());
        $this->assertSame('expirada', $expirada->statusAssinatura());
    }
}

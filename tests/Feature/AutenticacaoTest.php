<?php

namespace Tests\Feature;

use App\Models\Usuario;
use App\Notifications\RedefinirSenhaNotification;
use App\Notifications\VerificarEmailNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class AutenticacaoTest extends TestCase
{
    use RefreshDatabase;

    private function novoUsuario(bool $verificado = true): Usuario
    {
        $usuario = Usuario::create([
            'nome' => 'Fulano',
            'email' => 'fulano' . uniqid() . '@teste.com',
            'password' => Hash::make('SenhaForte123'),
            'trial_ends_at' => now()->addDays(14),
        ]);

        // email_verified_at não é mass-assignable (proteção); marca via API própria.
        if ($verificado) {
            $usuario->markEmailAsVerified();
        }

        return $usuario;
    }

    public function test_registro_cria_usuario_com_trial_categorias_e_email_nao_verificado(): void
    {
        $resposta = $this->post('/register', [
            'nome' => 'Novo Usuario',
            'email' => 'novo@teste.com',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
            'aceite_termos' => '1',
        ]);

        $usuario = Usuario::where('email', 'novo@teste.com')->first();

        $this->assertNotNull($usuario);
        $this->assertNotNull($usuario->trial_ends_at);
        $this->assertNotNull($usuario->termos_aceitos_em, 'Deve registrar o aceite dos termos');
        $this->assertNull($usuario->email_verified_at, 'E-mail deve começar não verificado');
        $this->assertCount(6, $usuario->categorias, 'Deve criar as categorias padrão');
    }

    public function test_usuario_nao_acessa_transacao_de_outro(): void
    {
        $dono = $this->novoUsuario();
        $categoria = $dono->categorias()->create(['nome' => 'Salário', 'tipo' => 'Receitas']);
        $transacao = $dono->transacoes()->create([
            'tipo' => 'Receitas',
            'descricao' => 'Salário',
            'valor' => 5000,
            'categoria_id' => $categoria->id,
        ]);

        $intruso = $this->novoUsuario();

        $this->actingAs($intruso)
            ->get(route('transacoes.edit', $transacao->id))
            ->assertForbidden();
    }

    public function test_dono_acessa_a_propria_transacao(): void
    {
        $dono = $this->novoUsuario();
        $categoria = $dono->categorias()->create(['nome' => 'Salário', 'tipo' => 'Receitas']);
        $transacao = $dono->transacoes()->create([
            'tipo' => 'Receitas',
            'descricao' => 'Salário',
            'valor' => 5000,
            'categoria_id' => $categoria->id,
        ]);

        $this->actingAs($dono)
            ->get(route('transacoes.edit', $transacao->id))
            ->assertOk();
    }

    public function test_usuario_nao_verificado_e_bloqueado_no_dashboard(): void
    {
        $usuario = $this->novoUsuario(verificado: false);

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertRedirect(route('verification.notice'));
    }

    public function test_usuario_verificado_acessa_o_dashboard(): void
    {
        $usuario = $this->novoUsuario();

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_pode_solicitar_link_de_recuperacao_de_senha(): void
    {
        $usuario = $this->novoUsuario();

        $resposta = $this->post('/forgot-password', ['email' => $usuario->email]);

        $resposta->assertSessionHasNoErrors();
        $resposta->assertSessionHas('status');
    }

    public function test_email_de_verificacao_esta_em_portugues(): void
    {
        $usuario = $this->novoUsuario(verificado: false);

        $mail = (new VerificarEmailNotification())->toMail($usuario);

        $this->assertStringContainsString('Confirme seu e-mail', $mail->subject);
        $this->assertSame('Confirmar e-mail', $mail->actionText);
    }

    public function test_email_de_reset_de_senha_esta_em_portugues(): void
    {
        $usuario = $this->novoUsuario();

        $mail = (new RedefinirSenhaNotification('token-de-teste'))->toMail($usuario);

        $this->assertStringContainsString('Redefinição de senha', $mail->subject);
        $this->assertSame('Redefinir senha', $mail->actionText);
    }

    public function test_emails_usam_o_nome_da_marca_configurado(): void
    {
        config(['app.name' => 'Marca De Teste']);
        $usuario = $this->novoUsuario(verificado: false);

        $verificacao = (new VerificarEmailNotification())->toMail($usuario);
        $reset = (new RedefinirSenhaNotification('token-de-teste'))->toMail($usuario);

        foreach ([$verificacao, $reset] as $mail) {
            $this->assertStringContainsString('Marca De Teste', $mail->subject);
            $this->assertStringContainsString('Marca De Teste', $mail->salutation);
        }
    }

    public function test_notificacoes_de_email_sao_enfileiradas(): void
    {
        $this->assertInstanceOf(ShouldQueue::class, new VerificarEmailNotification());
        $this->assertInstanceOf(ShouldQueue::class, new RedefinirSenhaNotification('token'));
    }

    public function test_registro_dispara_email_de_verificacao(): void
    {
        Notification::fake();

        $this->post('/register', [
            'nome' => 'Novo Usuario',
            'email' => 'novo@teste.com',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
            'aceite_termos' => '1',
        ]);

        $usuario = Usuario::where('email', 'novo@teste.com')->first();

        Notification::assertSentTo($usuario, VerificarEmailNotification::class);
    }

    public function test_solicitar_reset_dispara_notificacao_de_senha(): void
    {
        Notification::fake();

        $usuario = $this->novoUsuario();

        $this->post('/forgot-password', ['email' => $usuario->email]);

        Notification::assertSentTo($usuario, RedefinirSenhaNotification::class);
    }

    public function test_registro_exige_aceite_dos_termos(): void
    {
        $resposta = $this->post('/register', [
            'nome' => 'Sem Aceite',
            'email' => 'semaceite@teste.com',
            'password' => 'SenhaForte123',
            'password_confirmation' => 'SenhaForte123',
            // sem aceite_termos
        ]);

        $resposta->assertSessionHasErrors('aceite_termos');
        $this->assertNull(Usuario::where('email', 'semaceite@teste.com')->first());
    }

    public function test_paginas_publicas_abrem_para_visitantes(): void
    {
        $this->get(route('home'))->assertOk();
        $this->get(route('planos'))->assertOk();
        $this->get(route('termos'))->assertOk();
        $this->get(route('privacidade'))->assertOk();
    }

    public function test_home_redireciona_usuario_logado_para_dashboard(): void
    {
        $usuario = $this->novoUsuario();

        $this->actingAs($usuario)
            ->get(route('home'))
            ->assertRedirect(route('dashboard'));
    }
}

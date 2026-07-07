<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
        ]);

        $usuario = Usuario::where('email', 'novo@teste.com')->first();

        $this->assertNotNull($usuario);
        $this->assertNotNull($usuario->trial_ends_at);
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
}

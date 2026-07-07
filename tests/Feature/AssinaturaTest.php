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

    public function test_trial_ativo_acessa_o_dashboard(): void
    {
        $usuario = $this->usuario(trial: now()->addDays(5)->toDateTimeString());

        $this->actingAs($usuario)->get('/dashboard')->assertOk();
    }

    public function test_trial_expirado_sem_assinatura_e_bloqueado(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->actingAs($usuario)
            ->get('/dashboard')
            ->assertRedirect(route('assinatura.expirada'));
    }

    public function test_assinatura_paga_acessa_mesmo_com_trial_expirado(): void
    {
        $usuario = $this->usuario(
            trial: now()->subDays(10)->toDateTimeString(),
            assinatura: now()->addMonth()->toDateTimeString(),
        );

        $this->actingAs($usuario)->get('/dashboard')->assertOk();
    }

    public function test_tela_de_assinatura_abre_para_usuario_bloqueado(): void
    {
        $usuario = $this->usuario(trial: now()->subDay()->toDateTimeString());

        $this->actingAs($usuario)
            ->get(route('assinatura.expirada'))
            ->assertOk();
    }

    public function test_tela_de_assinatura_redireciona_quem_tem_acesso(): void
    {
        $usuario = $this->usuario(trial: now()->addDays(5)->toDateTimeString());

        $this->actingAs($usuario)
            ->get(route('assinatura.expirada'))
            ->assertRedirect(route('dashboard'));
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

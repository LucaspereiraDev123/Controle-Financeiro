<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AssinaturaPagamentoTest extends TestCase
{
    use RefreshDatabase;

    private function usuarioExpirado(): Usuario
    {
        $usuario = Usuario::create([
            'nome' => 'Fulano',
            'email' => 'fulano' . uniqid() . '@teste.com',
            'password' => Hash::make('SenhaForte123'),
            'trial_ends_at' => now()->subDay(),
            'termos_aceitos_em' => now(),
        ]);
        $usuario->markEmailAsVerified();

        return $usuario;
    }

    public function test_checkout_cria_assinatura_e_redireciona_para_o_mercado_pago(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);

        Http::fake([
            'https://api.mercadopago.com/preapproval' => Http::response([
                'id' => 'PRE123',
                'init_point' => 'https://www.mercadopago.com.br/checkout/PRE123',
            ], 201),
        ]);

        $usuario = $this->usuarioExpirado();

        $this->actingAs($usuario)
            ->post(route('assinatura.checkout'))
            ->assertRedirect('https://www.mercadopago.com.br/checkout/PRE123');

        $this->assertSame('PRE123', $usuario->fresh()->mp_preapproval_id);
    }

    public function test_checkout_sem_token_configurado_volta_com_mensagem(): void
    {
        config(['services.mercadopago.access_token' => null]);

        $usuario = $this->usuarioExpirado();

        $this->actingAs($usuario)
            ->post(route('assinatura.checkout'))
            ->assertRedirect(route('planos'))
            ->assertSessionHas('msg');
    }

    /**
     * Quem está no trial pode converter antes do prazo acabar — é o caminho da
     * sidebar, e a guarda antiga o mandava de volta ao dashboard.
     */
    public function test_checkout_permitido_para_usuario_em_trial(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);

        Http::fake([
            'https://api.mercadopago.com/preapproval' => Http::response([
                'id' => 'PRE456',
                'init_point' => 'https://www.mercadopago.com.br/checkout/PRE456',
            ], 201),
        ]);

        $usuario = $this->usuarioExpirado();
        $usuario->update(['trial_ends_at' => now()->addDays(5)]);

        $this->actingAs($usuario)
            ->post(route('assinatura.checkout'))
            ->assertRedirect('https://www.mercadopago.com.br/checkout/PRE456');
    }

    public function test_checkout_bloqueado_para_quem_ja_assina(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);
        Http::fake();

        $usuario = $this->usuarioExpirado();
        $usuario->update(['assinatura_ativa_ate' => now()->addMonth()]);

        $this->actingAs($usuario)
            ->post(route('assinatura.checkout'))
            ->assertRedirect(route('dashboard'));

        // Não pode nascer um segundo preapproval no Mercado Pago.
        Http::assertNothingSent();
    }

    public function test_webhook_ativa_assinatura_quando_autorizada(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);

        $usuario = $this->usuarioExpirado();
        $usuario->update(['mp_preapproval_id' => 'PRE123']);

        Http::fake([
            'https://api.mercadopago.com/preapproval/*' => Http::response([
                'id' => 'PRE123',
                'status' => 'authorized',
                'external_reference' => (string) $usuario->id,
            ], 200),
        ]);

        $this->postJson(route('webhooks.mercadopago'), [
            'type' => 'preapproval',
            'data' => ['id' => 'PRE123'],
        ])->assertOk();

        $usuario->refresh();
        $this->assertNotNull($usuario->assinatura_ativa_ate);
        $this->assertTrue($usuario->assinaturaAtiva());
    }

    public function test_webhook_nao_ativa_quando_status_pendente(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);

        $usuario = $this->usuarioExpirado();
        $usuario->update(['mp_preapproval_id' => 'PRE999']);

        Http::fake([
            'https://api.mercadopago.com/preapproval/*' => Http::response([
                'id' => 'PRE999',
                'status' => 'pending',
                'external_reference' => (string) $usuario->id,
            ], 200),
        ]);

        $this->postJson(route('webhooks.mercadopago'), [
            'type' => 'preapproval',
            'data' => ['id' => 'PRE999'],
        ])->assertOk();

        $this->assertNull($usuario->fresh()->assinatura_ativa_ate);
    }
}

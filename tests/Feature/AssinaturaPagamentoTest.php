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

    /**
     * Cliente com assinatura paga vigente, ainda não cancelada.
     */
    private function usuarioAssinante(): Usuario
    {
        $usuario = $this->usuarioExpirado();
        $usuario->update([
            'assinatura_ativa_ate' => now()->addDays(20),
            'mp_preapproval_id' => 'PRE-ATIVO',
        ]);

        return $usuario->fresh();
    }

    public function test_cancelamento_encerra_a_assinatura_no_mercado_pago(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);
        Http::fake(['https://api.mercadopago.com/preapproval/*' => Http::response(['status' => 'cancelled'], 200)]);

        $usuario = $this->usuarioAssinante();

        $this->actingAs($usuario)
            ->delete(route('assinatura.cancelar.confirmar'))
            ->assertRedirect(route('conta'))
            ->assertSessionHas('msg');

        Http::assertSent(fn ($req) => $req->method() === 'PUT'
            && str_contains($req->url(), '/preapproval/PRE-ATIVO')
            && $req['status'] === 'cancelled');

        $this->assertNotNull($usuario->fresh()->assinatura_cancelada_em);
    }

    /**
     * O cliente pagou o mês: cancelar não pode tirar o acesso na hora — é o que
     * os Termos prometem.
     */
    public function test_cancelamento_preserva_o_acesso_ja_pago(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);
        Http::fake(['https://api.mercadopago.com/preapproval/*' => Http::response([], 200)]);

        $usuario = $this->usuarioAssinante();
        $validoAte = $usuario->assinatura_ativa_ate;

        $this->actingAs($usuario)->delete(route('assinatura.cancelar.confirmar'));

        $usuario->refresh();
        $this->assertTrue($usuario->assinaturaAtiva());
        $this->assertTrue($usuario->podeEditar());
        $this->assertEquals($validoAte, $usuario->assinatura_ativa_ate);
        $this->actingAs($usuario)->get('/dashboard')->assertOk()->assertSee('Nova transação');
    }

    public function test_falha_no_gateway_nao_marca_como_cancelada(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);
        Http::fake(['https://api.mercadopago.com/preapproval/*' => Http::response(['erro' => 'x'], 500)]);

        $usuario = $this->usuarioAssinante();

        $this->actingAs($usuario)
            ->delete(route('assinatura.cancelar.confirmar'))
            ->assertRedirect(route('conta'))
            ->assertSessionHas('msg');

        // Se o MP recusou, a cobrança segue de pé: não podemos dizer que cancelou.
        $this->assertNull($usuario->fresh()->assinatura_cancelada_em);
    }

    public function test_cancelar_de_novo_nao_faz_nada(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);
        Http::fake();

        $usuario = $this->usuarioAssinante();
        $usuario->update(['assinatura_cancelada_em' => now()->subDay()]);

        $this->actingAs($usuario)
            ->delete(route('assinatura.cancelar.confirmar'))
            ->assertRedirect(route('conta'));

        Http::assertNothingSent();
    }

    public function test_quem_nao_assina_nao_acessa_o_cancelamento(): void
    {
        $usuario = $this->usuarioExpirado();

        $this->actingAs($usuario)->get(route('assinatura.cancelar'))->assertRedirect(route('conta'));
        $this->actingAs($usuario)->delete(route('assinatura.cancelar.confirmar'))->assertRedirect(route('conta'));
    }

    /**
     * Acesso liberado à mão pelo admin não tem preapproval no gateway.
     */
    public function test_acesso_dado_pelo_admin_nao_oferece_cancelamento(): void
    {
        $usuario = $this->usuarioExpirado();
        $usuario->update(['assinatura_ativa_ate' => now()->addDays(30)]);

        $this->assertFalse($usuario->fresh()->podeCancelar());
        $this->actingAs($usuario)->get(route('assinatura.cancelar'))->assertRedirect(route('conta'));
    }

    public function test_tela_de_cancelamento_avisa_o_direito_de_arrependimento(): void
    {
        $usuario = $this->usuarioAssinante();

        $this->actingAs($usuario)
            ->get(route('assinatura.cancelar'))
            ->assertOk()
            ->assertSee('7 dias')
            ->assertSee('contato@economizacerto.com.br')
            ->assertSee($usuario->assinatura_ativa_ate->format('d/m/Y'));
    }

    public function test_conta_oferece_cancelamento_e_mostra_a_renovacao(): void
    {
        $usuario = $this->usuarioAssinante();

        $this->actingAs($usuario)
            ->get(route('conta'))
            ->assertOk()
            ->assertSee('Cancelar assinatura')
            ->assertSee('Automática (mensal)');

        $usuario->update(['assinatura_cancelada_em' => now()]);

        $this->actingAs($usuario)
            ->get(route('conta'))
            ->assertOk()
            ->assertSee('sem novas cobranças')
            ->assertDontSee('Automática (mensal)')
            ->assertDontSee('Cancelar assinatura');
    }

    /**
     * O cliente pode cancelar direto na conta dele do Mercado Pago; o webhook é
     * a única forma de o sistema ficar sabendo.
     */
    public function test_webhook_registra_cancelamento_feito_no_mercado_pago(): void
    {
        config(['services.mercadopago.access_token' => 'TEST-token']);

        $usuario = $this->usuarioAssinante();

        Http::fake([
            'https://api.mercadopago.com/preapproval/*' => Http::response([
                'id' => 'PRE-ATIVO',
                'status' => 'cancelled',
                'external_reference' => (string) $usuario->id,
            ], 200),
        ]);

        $this->postJson(route('webhooks.mercadopago'), [
            'type' => 'preapproval',
            'data' => ['id' => 'PRE-ATIVO'],
        ])->assertOk();

        $usuario->refresh();
        $this->assertNotNull($usuario->assinatura_cancelada_em);
        // O período pago continua valendo.
        $this->assertTrue($usuario->assinaturaAtiva());
    }
}

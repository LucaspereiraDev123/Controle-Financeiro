<?php

namespace Tests\Feature;

use App\Models\Usuario;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use RefreshDatabase;

    private function criarUsuario(array $atributos = []): Usuario
    {
        $usuario = Usuario::create(array_merge([
            'nome' => 'Fulano',
            'email' => 'fulano' . uniqid() . '@teste.com',
            'password' => Hash::make('SenhaForte123'),
            'trial_ends_at' => now()->addDays(10),
            'termos_aceitos_em' => now(),
        ], $atributos));
        $usuario->markEmailAsVerified();

        return $usuario;
    }

    public function test_usuario_comum_nao_acessa_o_admin(): void
    {
        $usuario = $this->criarUsuario();

        $this->actingAs($usuario)->get(route('admin.dashboard'))->assertForbidden();
        $this->actingAs($usuario)->get(route('admin.usuarios'))->assertForbidden();
    }

    public function test_admin_acessa_dashboard_e_usuarios(): void
    {
        $admin = $this->criarUsuario(['is_admin' => true]);

        $this->actingAs($admin)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Receita mensal estimada (MRR)')
            ->assertSee('Assinantes ativos');

        $this->actingAs($admin)->get(route('admin.usuarios'))->assertOk();
    }

    public function test_admin_estende_acesso_de_um_usuario(): void
    {
        $admin = $this->criarUsuario(['is_admin' => true]);
        $alvo = $this->criarUsuario(['trial_ends_at' => now()->subDay()]);

        $this->actingAs($admin)
            ->post(route('admin.usuarios.estender', $alvo->id), ['dias' => 30])
            ->assertRedirect();

        $alvo->refresh();
        $this->assertTrue($alvo->assinatura_ativa_ate->isFuture());
        $this->assertTrue($alvo->assinaturaAtiva());
    }

    public function test_admin_bloqueia_acesso_de_um_usuario(): void
    {
        $admin = $this->criarUsuario(['is_admin' => true]);
        $alvo = $this->criarUsuario([
            'trial_ends_at' => now()->addDays(5),
            'assinatura_ativa_ate' => now()->addDays(30),
        ]);

        $this->actingAs($admin)
            ->post(route('admin.usuarios.bloquear', $alvo->id))
            ->assertRedirect();

        $alvo->refresh();
        $this->assertFalse($alvo->assinaturaAtiva());
        $this->assertSame('expirada', $alvo->statusAssinatura());
    }

    public function test_comando_promove_e_rebaixa_admin(): void
    {
        $usuario = $this->criarUsuario();

        $this->artisan('admin:promover', ['email' => $usuario->email])->assertSuccessful();
        $this->assertTrue($usuario->fresh()->is_admin);

        $this->artisan('admin:promover', ['email' => $usuario->email, '--remover' => true])->assertSuccessful();
        $this->assertFalse($usuario->fresh()->is_admin);
    }
}

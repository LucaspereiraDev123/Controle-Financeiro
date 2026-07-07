<?php

namespace App\Actions\Fortify;

use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     *
     * @throws ValidationException
     */
    public function create(array $input): Usuario
    {
        Validator::make($input, [
            'nome' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(Usuario::class),
            ],
            'password' => $this->passwordRules(),
            // consentimento LGPD: o checkbox precisa vir marcado.
            'aceite_termos' => ['accepted'],
        ], [
            'aceite_termos.accepted' => 'É preciso aceitar os Termos de Uso e a Política de Privacidade.',
        ])->validate();

        $usuario = Usuario::create([
            'nome' => $input['nome'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            // período de teste inicial; a lógica de billing entra na Fase 3/4
            'trial_ends_at' => now()->addDays(14),
            // registra o momento do consentimento (LGPD)
            'termos_aceitos_em' => now(),
        ]);

        $this->criarCategoriasPadrao($usuario);

        return $usuario;
    }

    /**
     * Cria um conjunto inicial de categorias para o novo usuário,
     * evitando que ele fique sem opções ao cadastrar a primeira transação.
     */
    private function criarCategoriasPadrao(Usuario $usuario): void
    {
        $usuario->categorias()->createMany([
            ['nome' => 'Salário', 'tipo' => 'Receitas'],
            ['nome' => 'Outras Receitas', 'tipo' => 'Receitas'],
            ['nome' => 'Alimentação', 'tipo' => 'Despesas'],
            ['nome' => 'Moradia', 'tipo' => 'Despesas'],
            ['nome' => 'Transporte', 'tipo' => 'Despesas'],
            ['nome' => 'Lazer', 'tipo' => 'Despesas'],
        ]);
    }
}

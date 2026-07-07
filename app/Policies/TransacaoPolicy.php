<?php

namespace App\Policies;

use App\Models\Transacao;
use App\Models\Usuario;

class TransacaoPolicy
{
    /**
     * Garante que o usuário só acesse suas próprias transações.
     */
    public function view(Usuario $usuario, Transacao $transacao): bool
    {
        return $transacao->usuario_id === $usuario->id;
    }

    public function update(Usuario $usuario, Transacao $transacao): bool
    {
        return $transacao->usuario_id === $usuario->id;
    }

    public function delete(Usuario $usuario, Transacao $transacao): bool
    {
        return $transacao->usuario_id === $usuario->id;
    }
}

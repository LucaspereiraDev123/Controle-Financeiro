<?php

namespace App\Policies;

use App\Models\Categoria;
use App\Models\Usuario;

class CategoriaPolicy
{
    /**
     * Garante que o usuário só acesse suas próprias categorias.
     */
    public function view(Usuario $usuario, Categoria $categoria): bool
    {
        return $categoria->usuario_id === $usuario->id;
    }

    public function update(Usuario $usuario, Categoria $categoria): bool
    {
        return $categoria->usuario_id === $usuario->id;
    }

    public function delete(Usuario $usuario, Categoria $categoria): bool
    {
        return $categoria->usuario_id === $usuario->id;
    }
}
